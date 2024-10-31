/**
 * Usage: `var req = Requests(); req.post("url", {}, {action : 1}`.
 *
 * @type {function(): QuizAdRequest}
 */
var QuizAdRequestFactory = (function (win) {

    /**
     * Set of internal utilities.
     *
     * @type {{getEncoding: (function(): string), encodeData: (function(*=): string), getBrowserRequestObject: (function(): boolean)}}
     */
    var ajax = {
        encodeData: function (data) {
            return Object.keys(data).map(function (key) {
                return key + '=' + (data[key] || '');
            })
                .join('&');
        },

        getEncoding: function () {
            return 'application/x-www-form-urlencoded';
        },

        getBrowserRequestObject : function () {
            var xhr = false;
            if (win.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            } else if (win.ActiveXObject) {
                try {
                    xhr = new XMLHttpRequest();
                } catch (e) {
                    try {
                        xhr = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {
                        xhr = false;
                    }
                }
            }
            return xhr;
        }
    };

    /**
     * Do nothing placeholder callback.
     */
    function noop() {}

    /**
     * The request class.
     *
     * @constructs QuizAdRequest
     */
    function QuizAdRequest () {
        this.xhr = ajax.getBrowserRequestObject();
        this._onErrorHandler = noop;
        this._onSuccessHandler = noop;
    }

    /**
     * Prepare onSuccess listener.
     *
     * @name QuizAdRequest#onSuccess
     * @param {function(status:int, response:string)} callback
     */
    QuizAdRequest.prototype.onSuccess = function (callback) {
        this._onSuccessHandler = callback;
    };

    /**
     * Prepare onError listener.
     *
     * @name QuizAdRequest#onError
     * @param {function(status:int, response:string)} callback
     */
    QuizAdRequest.prototype.onError = function (callback) {
        this._onErrorHandler = callback;
    };

    /**
     * @private
     */
    QuizAdRequest.prototype._setupHandlers = function () {

        var that = this;
        this.xhr.onerror = function (e) {
            that._onErrorHandler(e);
        };
        this.xhr.onreadystatechange = function () {
            if (that.xhr.readyState === that.xhr.DONE) {
                if (that.xhr.status < 400) {
                    that._onSuccessHandler(that.xhr.statusCode, that.xhr.responseText);
                } else {
                    that._onErrorHandler(that.xhr.statusCode, that.xhr.responseText);
                }
            }
        }
    };

    /**
     * Perform POST request.
     *
     * @name QuizAdRequest#post
     * @param {string} url
     * @param {object} options
     * @param {object} postData
     */
    QuizAdRequest.prototype.post = function (url, options, postData) {
        var opts = options || {};
        var data = postData || {};
        this.xhr.open( 'POST', url);
        this.xhr.setRequestHeader('Content-Type', ajax.getEncoding());
        this._setupHandlers();

        this.xhr.send(ajax.encodeData(data));
    };

    /**
     * Perform GET request.
     *
     * @name QuizAdRequest#get
     * @param {string} url
     * @param {object} options
     */
    QuizAdRequest.prototype.get = function (url, options) {
        var opts = options || {};
        this.xhr.open( 'GET', url);
        this.xhr.setRequestHeader('Content-Type', ajax.getEncoding());
        this._setupHandlers();

        this.xhr.send();
    };

    /**
     * Factory method to return request class object.
     *
     * @return {QuizAdRequest}
     */
    var factory = function () {
        // factory function
        return new QuizAdRequest();
    };

    return factory;
})(window);