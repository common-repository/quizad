<?php

namespace QuizAd\Service\Wordpress;

use QuizAd\Service\Placements\HeaderCodeApiService;
use QuizAd\Service\Placements\PlacementsService;
use WP_Query;

class PageService
{
    protected $excludeTags       = array('0' => '0');
    protected $excludeCategories = array('0' => '0');
    protected $includeCategories = array('0' => '0');
    protected $excludePost       = array('0' => '0');
    protected $excludePage       = array('0' => '0');
    protected $adPlace           = array('0' => '0');
    /** @var PlacementsService */
    private $placementService;
    /** @var HeaderCodeApiService */
    private $websiteHeaderCode;

    /**
     * PageService constructor.
     * @param PlacementsService $placementService
     */
    public function __construct(PlacementsService $placementService, HeaderCodeApiService $websiteHeaderCode)
    {
        $this->placementService  = $placementService;
        $this->websiteHeaderCode = $websiteHeaderCode;
    }

    /**
     * The content filer with add placements to current page.
     * @param $excludedPositions
     * @param $checkedPositions
     */
    public function addPlacementsToCurrentPage($checkedPositions, $excludedPositions)
    {
        $this->preparePlacementPositions($checkedPositions, $excludedPositions);
        add_action('loop_start', function (WP_Query $query) {
            if ($query->is_main_query()) {
                add_filter('the_content', [$this, 'quizAd_insertPlacement'], 99);
            }
        });

        add_action('loop_end', function (WP_Query $query) {
            if (has_filter('the_content', [$this, 'quizAd_insertPlacement'])) {
                remove_filter('the_content', [$this, 'quizAd_insertPlacement']);
            }
        });
    }

    /**
     * @param $content
     * @return string
     */
    public function quizAd_insertPlacement($content)
    {
        $defaultPlacement = $this->placementService->getPlacements()->getDefaultPlacement();
        if ($defaultPlacement
            && is_singular(array('post'))
            && (in_array('posts', $this->adPlace))
            && (in_category($this->includeCategories))
            && (!has_tag($this->excludeTags))
            && !in_array(get_the_ID(), $this->excludePost)) {
            return $this->appendInToContent($content, $defaultPlacement);
        } elseif ($defaultPlacement
            && (is_page())
            && (in_array('pages', $this->adPlace))
            && !in_array(get_the_ID(), $this->excludePage)) {
            return $this->appendInToContent($content, $defaultPlacement);
        } else {
            return $content;
        }

    }

    /**
     * @param $content
     * @param $defaultPlacement
     * @return string
     */
    protected function appendInToContent($content, $defaultPlacement)
    {
        add_action('wp_head', function () use ($defaultPlacement) {
            echo $defaultPlacement->getHeaderCode();
        });
        $sentencesNuder = $defaultPlacement->getPlacementSentence();
        $placementCode  = $defaultPlacement->getHtmlCode();

        /** @var array $placementId - part of placement code to check is code injected before */
        $placementId = explode('<div ', explode('></div><script>', $placementCode)[0]);

        $isInjected  = strpos($content, $placementId[1]);
        if ($isInjected) {
            return $content;
        }

        return $this->insetAfterSentence($content, $placementCode, $sentencesNuder);
    }

    /**
     * @param $content
     * @param $placementCode
     * @param $sentencesNuder
     * @return string
     */
    protected function insetAfterSentence($content, $placementCode, $sentencesNuder)
    {
        /** @var array $sentences - get content sentences */
        $sentences = preg_split('/(?<![0-9]\.)(?<!\.\.\.)(?<=[.?!]|\.\))[\s\r\n]*(?=[A-Z]|<.*?>|$)/m', $content);
        /** when article is to short do not insert advertisement */
        if ($sentencesNuder > count($sentences) - 10) {
            return $content;
        }

        /** TODO: replace to white list and available tags */
        /** @var string $tag - html tag */
        foreach (explode('<', $sentences[$sentencesNuder]) as $tag) {
            if (strpos($tag, 'figcaption') !== false) {
                $sentencesNuder += 2;
            }
        }

        while (!$this->canInjectPlacementCodeInToActualSentence($sentences[$sentencesNuder - 1])) {
            $sentencesNuder++;
        }


        /** check is paragraph open - browser remove close paragraph and automatically added new before div. */
        if ($this->isOpenParagraph($sentences, $sentencesNuder)) {
            foreach ($sentences as $index => $sentence) {
                if ($sentencesNuder == $index + 1) {
                    $sentences[$index] .= '</p>' . $placementCode . '<p>';
                }
            }
        } else {
            foreach ($sentences as $index => $sentence) {
                if ($sentencesNuder == $index + 1) {
                    $sentences[$index] .= $placementCode;
                }
            }
        }


        $content = implode(' ', $sentences);
        /** @var array $explodedAdDiv - append close div after content */
        $explodedAdDiv = explode('"></div>', explode('<script>', $placementCode)[0]);
//        var_dump($placementCode);die();
        $content .= $explodedAdDiv[0] . '-pwe"></div>';

        return $content;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return array
     */
    protected function strpos_all($haystack, $needle)
    {
        $offset    = 0;
        $allResult = array();
        while (($position = strpos($haystack, $needle, $offset)) !== FALSE) {
            $offset      = $position + 1;
            $allResult[] = $position;
        }
        return $allResult;
    }

    /**
     * Return the closest sentence number
     * @param $sentences
     * @param $sentenceNum
     * @return bool
     */
    protected function isWhiteList($sentences, $sentenceNum)
    {
        $contentToCode = implode(' ', array_slice($sentences, $sentenceNum, $sentenceNum + 1));
        $allTags       = explode('<', $contentToCode);
//        $lastContent = $allTags[count($allTags) - 1];
//        $lastTag     = explode(' ', $lastContent);
        foreach ($allTags as $allTag) {
            if (!in_array($allTag, ['div', 'p>'])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check is open paragraph after insert placement code.
     * @param $sentences
     * @param $sentenceNum
     * @return bool
     */
    private function isOpenParagraph($sentences, $sentenceNum)
    {
        $contentToCode  = implode(' ', array_slice($sentences, 0, $sentenceNum + 1));
        $openParagraph  = count($this->strpos_all($contentToCode, '<p>'));
        $closeParagraph = count($this->strpos_all($contentToCode, '</p>'));
        if ($openParagraph > $closeParagraph) {
            return true;
        }
        return false;
    }

    /**
     * @param $checked
     * @param $excluded
     */
    private function preparePlacementPositions($checked, $excluded)
    {
        array_map(function ($check) {
            $typeAndId = explode('-', $check);
            if ($typeAndId[0] === 'category') {
                array_push(
                    $this->includeCategories,
                    $typeAndId[1]);
            }
            if ($typeAndId[0] === 'pp') {
                array_push(
                    $this->adPlace,
                    $typeAndId[1]);
            }
        }, $checked);
        array_map(function ($exclude) {
            $typeAndId = explode('-', $exclude);
            if ($typeAndId[0] === 'post') {
                array_push(
                    $this->excludePost,
                    $typeAndId[1]);
            }
            if ($typeAndId[0] === 'page') {
                array_push(
                    $this->excludePage,
                    $typeAndId[1]);
            }
            if ($typeAndId[0] === 'tag') {
                array_push(
                    $this->excludeTags,
                    $typeAndId[1]);
            }
        }, $excluded);
    }

    /**
     * @param $sentence
     * @return bool
     */
    private function canInjectPlacementCodeInToActualSentence($sentence)
    {
        if (
            /** check sentence count 4 word */
            str_word_count($sentence) < 3
            /** check is not shortcut */
            || $this->isShortcut($sentence)
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param $sentence
     * @return bool
     */
    private function isShortcut($sentence)
    {
        $pieces   = explode(' ', $sentence);
        $lastWord = array_pop($pieces);
        return in_array($lastWord, $this->getShortcutList());
    }

    /**
     * @return string[]
     */
    private function getShortcutList()
    {
        return [
            '0', '0.', '1', '1.', '10.', '2', '2.', '3', '3.', '4', '4.', '5', '5.', '6', '6.', '7', '7.', '8', '8.',
            '9', '9.', 'A', 'a.', 'A.', 'a6w', 'A6W', 'Ab', 'abp', 'abpstwo', 'ac', 'afr.', 'Ag', 'ags.', 'al.', 'Al.',
            'alb.', 'Am', 'ang.', 'aor.', 'Ap', 'ap.', 'apost.', 'arcyks.', 'art.', 'asp.', 'aust.', 'austral.', 'B',
            'b.', 'Ba', 'bbq', 'BCh', 'bdb.', 'belg.', 'białorus.', 'białost.', 'bł.', 'blm', 'blm.', 'blp', 'blp.',
            'bm.', 'bot.', 'bp', 'bp.', 'bpstwo', 'br.', 'bryt.', 'bsm.', 'bułg.', 'C', 'c.b.d.o.', 'CBDO', 'cbdu.',
            'CBŚ', 'cd.', 'cdn.', 'ChD', 'chor.', 'chorw.', 'cieśn.', 'ckm', 'cnd.', 'Cz-wa', 'czes.', 'czw.', 'czyt.',
            'D', 'd', 'd-ca', 'd.', 'daw.', 'db', 'def.', 'dh', 'dk.', 'Dn', 'dn.', 'doc.', 'doktor h.c.', 'dol.',
            'dolnośl.', 'dot.', 'dr', 'dr h.c.', 'dr hab.', 'DS', 'ds.', 'dst.', 'dyr.', 'Dz', 'E', 'Ef', 'EKG',
            'ekum.', 'ent.', 'Est', 'est.', 'etym.', 'europ.', 'ew.', 'Ez', 'Ezd', 'F', 'f-ka', 'f-ma', 'Flm', 'flor.',
            'Flp', 'fot.', 'fp', 'franc.', 'funt. szt.', 'fz', 'G', 'g', 'g.', 'Ga', 'gd.', 'gen.', 'gm.', 'godz.',
            'gorz.', 'gr', 'gr.', 'grub.', 'GUS', 'H', 'H', 'h.c.', 'Ha', 'hab.', 'harc.', 'Hbr', 'hebr.', 'Hi',
            'hiszp.', 'hitl.', 'hm.', 'HO', 'I', 'in.', 'i in.', 'ie.', 'im.', 'inż.', 'itd.', 'itp.', 'Iz', 'J', 'J',
            'j.', 'j.a.', 'Jdt', 'jez.', 'Jk', 'Jl', 'jn.', 'Jon', 'Joz', 'Jr', 'Jud', 'jw.', 'jwt.', 'K', 'K-ce',
            'K-ów', 'k.', 'k.k.', 'k.p.a.', 'k.p.c.', 'kadm.', 'kard.', 'kark.', 'kasz.', 'kat.', 'kier.', 'kl.', 'kł.',
            'km', 'kmdr', 'Koh', 'Kol', 'kol.', 'kontradm.', 'Kor', 'kpk', 'Kpł', 'kpr.', 'kpt.', 'krak.', 'Krl', 'Krn',
            'ks.', 'książk.', 'kuj.', 'L', 'l.', 'Lb', 'lek.', 'lit.', 'Łk', 'lm', 'L', 'Lm', 'log', 'łot.', 'łow.',
            'lp', 'lp.', 'lub.', 'Łuk.', 'M', 'M', 'm.', 'm.b.', 'm.in.', 'm.p.', 'm.st.', 'małop.', 'mar.', 'Mat.',
            'maz.', 'MB', 'Mch', 'Mdr', 'med.', 'MEN', 'mgr', 'Mi', 'min', 'min.', 'mjr', 'mk', 'Mk', 'Ml', 'mł.',
            'mł. chor.', 'mld', 'mln', 'mn.', 'mn.w.', 'Mojż.', 'Ms', 'Mt', 'muz.', 'N', 'n.', 'n.e.', 'n.p.m.',
            'n.p.u.', 'Na', 'nb.', 'ndst', 'Ne', 'niedz.', 'niem.', 'NIK', 'norw.', 'np.', 'nr', 'nt.', 'nż.', 'O',
            'o.', 'ob.', 'Obj', 'odc.', 'odp.', 'ok.', 'oo.', 'op.', 'os.', 'Oz', 'P', 'P', 'p-ko', 'p-ta', 'p.',
            'p.a.', 'p.f.', 'p.f.v.', 'p.n.e.', 'p.o.', 'p.p.', 'p.p.m.', 'p.r.', 'p.r.v.', 'PGE', 'phm.', 'pie.',
            'pkt', 'pl.', 'płd.', 'płk', 'płn.', 'plut.', 'płw.', 'pn.', 'Pnp', 'po Chr.', 'pocz.', 'pod.', 'podgat.',
            'podkarp.', 'poet.', 'poj.', 'pol.', 'poł.', 'półn.', 'pom.', 'pon.', 'poprz.', 'por.', 'port.', 'posp.',
            'pot.', 'pow.', 'poz.', 'pp', 'pp.', 'ppanc.', 'ppł', 'ppłk', 'ppor.', 'ppoż.', 'prawdop.', 'proc.',
            'prof.', 'prof. nadzw.', 'Prz', 'przed Chr.', 'przyp.', 'Ps', 'ps.', 'pseud.', 'pt.', 'pw.', 'Pwt', 'Q',
            'q.l.', 'q.p.', 'R', 'r.', 'r.ż.', 'rad', 'Rdz', 'red.', 'rgt', 'ros.', 'rozdz.', 'RPA', 'Rt', 'rtg.',
            'rtm.', 'rum.', 'Rut', 'ryc.', 'Rz', 'rz.', 'S', 's', 's-ka', 's.', 'SdPl', 'Sdz', 'sek', 'serb.', 'sierż.',
            'skr.', 'śl.', 'słow.', 'Sm', 'So', 'sob.', 'śp.', 'sp. z o.o.', 'śr.', 'St-ce', 'st.', 'st. bsm.',
            'st. kpr.', 'st. sierż.', 'st. szer.', 'st.rus.', 'str.', 'sud.', 'św.', 'Syr', 'Sz.P.', 'szczec.', 'szer.',
            'szt.', 'szw.', 'szwajc.', 'T', 't', 't.', 't.j.', 'tatrz.', 'Tb', 'tel.', 'TERYT', 'Tes', 'tj.', 'tłum.',
            'Tm', 'tow.', 'trl.', 'tryb.', 'ts.', 'Tt', 'T', 'Tu', 'tur.', 'turec.', 'tut.', 'tys.', 'Tyt', 'tzn.',
            'tzw.', 'U', 'ub.', 'ukr.', 'ul.', 'UPR', 'ur.', 'V', 'v.v.', 'vs.', 'W', 'W', 'W-w', 'W-wa', 'w.', 'wadm.',
            'warm.', 'wędr.', 'węg.', 'wg', 'wgl', 'wiceadm.', 'wiej.', 'Wj', 'wł.', 'wlk.', 'wlkp.', 'woj.', 'wroc.',
            'ws.', 'wsch.', 'wt.', 'ww.', 'wyb.', 'wyd.', 'Wyj', 'wyj.', 'wym.', 'wyż.', 'wzgl.', 'X', 'x.', 'xx.', 'Z',
            'ż.', 'Za', 'ząbk.', 'zach.', 'żarg.', 'żart.', 'zdr.', 'ziel.', 'zł', 'zm.', 'zob.', 'zool.', 'ZSL', 'zw.',
            'żyd.'
        ];
    }
}