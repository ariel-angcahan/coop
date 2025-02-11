/*!

 * Pusher JavaScript Library v4.3.1

 * https://pusher.com/

 *

 * Copyright 2017, Pusher

 * Released under the MIT licence.

 */
! function(t, e) {
    "object" == typeof exports && "object" == typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define([], e) : "object" == typeof exports ? exports.Pusher = e() : t.Pusher = e()
}(this, function() {
    return function(t) {
        function e(r) {
            if (n[r]) return n[r].exports;
            var i = n[r] = {
                exports: {},
                id: r,
                loaded: !1
            };
            return t[r].call(i.exports, i, i.exports, e), i.loaded = !0, i.exports
        }
        var n = {};
        return e.m = t, e.c = n, e.p = "", e(0)
    }([function(t, e, n) {
        "use strict";
        var r = n(1);
        t.exports = r.default
    }, function(t, e, n) {
        "use strict";

        function r(t) {
            if (null === t || void 0 === t) throw "You must pass your app key when you instantiate Pusher."
        }
        var i = n(2),
            o = n(9),
            s = n(24),
            a = n(39),
            u = n(40),
            c = n(41),
            h = n(12),
            f = n(5),
            l = n(71),
            p = n(8),
            d = n(43),
            y = n(14),
            g = function() {
                function t(e, n) {
                    var h = this;
                    if (r(e), n = n || {}, !n.cluster && !n.wsHost && !n.httpHost) {
                        var g = y.default.buildLogSuffix("javascriptQuickStart");
                        p.default.warn("You should always specify a cluster when connecting. " + g)
                    }
                    this.key = e, this.config = o.extend(l.getGlobalConfig(), n.cluster ? l.getClusterConfig(n.cluster) : {}, n), this.channels = d.default.createChannels(), this.global_emitter = new s.default, this.sessionID = Math.floor(1e9 * Math.random()), this.timeline = new a.default(this.key, this.sessionID, {
                        cluster: this.config.cluster,
                        features: t.getClientFeatures(),
                        params: this.config.timelineParams || {},
                        limit: 50,
                        level: u.default.INFO,
                        version: f.default.VERSION
                    }), this.config.disableStats || (this.timelineSender = d.default.createTimelineSender(this.timeline, {
                        host: this.config.statsHost,
                        path: "/timeline/v2/" + i.default.TimelineTransport.name
                    }));
                    var v = function(t) {
                        var e = o.extend({}, h.config, t);
                        return c.build(i.default.getDefaultStrategy(e), e)
                    };
                    this.connection = d.default.createConnectionManager(this.key, o.extend({
                        getStrategy: v,
                        timeline: this.timeline,
                        activityTimeout: this.config.activity_timeout,
                        pongTimeout: this.config.pong_timeout,
                        unavailableTimeout: this.config.unavailable_timeout
                    }, this.config, {
                        useTLS: this.shouldUseTLS()
                    })), this.connection.bind("connected", function() {
                        h.subscribeAll(), h.timelineSender && h.timelineSender.send(h.connection.isUsingTLS())
                    }), this.connection.bind("message", function(t) {
                        var e = 0 === t.event.indexOf("pusher_internal:");
                        if (t.channel) {
                            var n = h.channel(t.channel);
                            n && n.handleEvent(t.event, t.data)
                        }
                        e || h.global_emitter.emit(t.event, t.data)
                    }), this.connection.bind("connecting", function() {
                        h.channels.disconnect()
                    }), this.connection.bind("disconnected", function() {
                        h.channels.disconnect()
                    }), this.connection.bind("error", function(t) {
                        p.default.warn("Error", t)
                    }), t.instances.push(this), this.timeline.info({
                        instances: t.instances.length
                    }), t.isReady && this.connect()
                }
                return t.ready = function() {
                    t.isReady = !0;
                    for (var e = 0, n = t.instances.length; e < n; e++) t.instances[e].connect()
                }, t.log = function(e) {
                    t.logToConsole && window.console && window.console.log && window.console.log(e)
                }, t.getClientFeatures = function() {
                    return o.keys(o.filterObject({
                        ws: i.default.Transports.ws
                    }, function(t) {
                        return t.isSupported({})
                    }))
                }, t.prototype.channel = function(t) {
                    return this.channels.find(t)
                }, t.prototype.allChannels = function() {
                    return this.channels.all()
                }, t.prototype.connect = function() {
                    if (this.connection.connect(), this.timelineSender && !this.timelineSenderTimer) {
                        var t = this.connection.isUsingTLS(),
                            e = this.timelineSender;
                        this.timelineSenderTimer = new h.PeriodicTimer(6e4, function() {
                            e.send(t)
                        })
                    }
                }, t.prototype.disconnect = function() {
                    this.connection.disconnect(), this.timelineSenderTimer && (this.timelineSenderTimer.ensureAborted(), this.timelineSenderTimer = null)
                }, t.prototype.bind = function(t, e, n) {
                    return this.global_emitter.bind(t, e, n), this
                }, t.prototype.unbind = function(t, e, n) {
                    return this.global_emitter.unbind(t, e, n), this
                }, t.prototype.bind_global = function(t) {
                    return this.global_emitter.bind_global(t), this
                }, t.prototype.unbind_global = function(t) {
                    return this.global_emitter.unbind_global(t), this
                }, t.prototype.unbind_all = function(t) {
                    return this.global_emitter.unbind_all(), this
                }, t.prototype.subscribeAll = function() {
                    var t;
                    for (t in this.channels.channels) this.channels.channels.hasOwnProperty(t) && this.subscribe(t)
                }, t.prototype.subscribe = function(t) {
                    var e = this.channels.add(t, this);
                    return e.subscriptionPending && e.subscriptionCancelled ? e.reinstateSubscription() : e.subscriptionPending || "connected" !== this.connection.state || e.subscribe(), e
                }, t.prototype.unsubscribe = function(t) {
                    var e = this.channels.find(t);
                    e && e.subscriptionPending ? e.cancelSubscription() : (e = this.channels.remove(t), e && "connected" === this.connection.state && e.unsubscribe())
                }, t.prototype.send_event = function(t, e, n) {
                    return this.connection.send_event(t, e, n)
                }, t.prototype.shouldUseTLS = function() {
                    return "https:" === i.default.getProtocol() || (this.config.forceTLS === !0 || Boolean(this.config.encrypted))
                }, t.instances = [], t.isReady = !1, t.logToConsole = !1, t.Runtime = i.default, t.ScriptReceivers = i.default.ScriptReceivers, t.DependenciesReceivers = i.default.DependenciesReceivers, t.auth_callbacks = i.default.auth_callbacks, t
            }();
        e.__esModule = !0, e.default = g, i.default.setup(g)
    }, function(t, e, n) {
        "use strict";
        var r = n(3),
            i = n(7),
            o = n(15),
            s = n(16),
            a = n(17),
            u = n(4),
            c = n(18),
            h = n(19),
            f = n(26),
            l = n(27),
            p = n(28),
            d = n(29),
            y = {
                nextAuthCallbackID: 1,
                auth_callbacks: {},
                ScriptReceivers: u.ScriptReceivers,
                DependenciesReceivers: r.DependenciesReceivers,
                getDefaultStrategy: l.default,
                Transports: h.default,
                transportConnectionInitializer: p.default,
                HTTPFactory: d.default,
                TimelineTransport: c.default,
                getXHRAPI: function() {
                    return window.XMLHttpRequest
                },
                getWebSocketAPI: function() {
                    return window.WebSocket || window.MozWebSocket
                },
                setup: function(t) {
                    var e = this;
                    window.Pusher = t;
                    var n = function() {
                        e.onDocumentBody(t.ready)
                    };
                    window.JSON ? n() : r.Dependencies.load("json2", {}, n)
                },
                getDocument: function() {
                    return document
                },
                getProtocol: function() {
                    return this.getDocument().location.protocol
                },
                getAuthorizers: function() {
                    return {
                        ajax: i.default,
                        jsonp: o.default
                    }
                },
                onDocumentBody: function(t) {
                    var e = this;
                    document.body ? t() : setTimeout(function() {
                        e.onDocumentBody(t)
                    }, 0)
                },
                createJSONPRequest: function(t, e) {
                    return new a.default(t, e)
                },
                createScriptRequest: function(t) {
                    return new s.default(t)
                },
                getLocalStorage: function() {
                    try {
                        return window.localStorage
                    } catch (t) {
                        return
                    }
                },
                createXHR: function() {
                    return this.getXHRAPI() ? this.createXMLHttpRequest() : this.createMicrosoftXHR()
                },
                createXMLHttpRequest: function() {
                    var t = this.getXHRAPI();
                    return new t
                },
                createMicrosoftXHR: function() {
                    return new ActiveXObject("Microsoft.XMLHTTP")
                },
                getNetwork: function() {
                    return f.Network
                },
                createWebSocket: function(t) {
                    var e = this.getWebSocketAPI();
                    return new e(t)
                },
                createSocketRequest: function(t, e) {
                    if (this.isXHRSupported()) return this.HTTPFactory.createXHR(t, e);
                    if (this.isXDRSupported(0 === e.indexOf("https:"))) return this.HTTPFactory.createXDR(t, e);
                    throw "Cross-origin HTTP requests are not supported"
                },
                isXHRSupported: function() {
                    var t = this.getXHRAPI();
                    return Boolean(t) && void 0 !== (new t).withCredentials
                },
                isXDRSupported: function(t) {
                    var e = t ? "https:" : "http:",
                        n = this.getProtocol();
                    return Boolean(window.XDomainRequest) && n === e
                },
                addUnloadListener: function(t) {
                    void 0 !== window.addEventListener ? window.addEventListener("unload", t, !1) : void 0 !== window.attachEvent && window.attachEvent("onunload", t)
                },
                removeUnloadListener: function(t) {
                    void 0 !== window.addEventListener ? window.removeEventListener("unload", t, !1) : void 0 !== window.detachEvent && window.detachEvent("onunload", t)
                }
            };
        e.__esModule = !0, e.default = y
    }, function(t, e, n) {
        "use strict";
        var r = n(4),
            i = n(5),
            o = n(6);
        e.DependenciesReceivers = new r.ScriptReceiverFactory("_pusher_dependencies", "Pusher.DependenciesReceivers"), e.Dependencies = new o.default({
            cdn_http: i.default.cdn_http,
            cdn_https: i.default.cdn_https,
            version: i.default.VERSION,
            suffix: i.default.dependency_suffix,
            receivers: e.DependenciesReceivers
        })
    }, function(t, e) {
        "use strict";
        var n = function() {
            function t(t, e) {
                this.lastId = 0, this.prefix = t, this.name = e
            }
            return t.prototype.create = function(t) {
                this.lastId++;
                var e = this.lastId,
                    n = this.prefix + e,
                    r = this.name + "[" + e + "]",
                    i = !1,
                    o = function() {
                        i || (t.apply(null, arguments), i = !0)
                    };
                return this[e] = o, {
                    number: e,
                    id: n,
                    name: r,
                    callback: o
                }
            }, t.prototype.remove = function(t) {
                delete this[t.number]
            }, t
        }();
        e.ScriptReceiverFactory = n, e.ScriptReceivers = new n("_pusher_script_", "Pusher.ScriptReceivers")
    }, function(t, e) {
        "use strict";
        var n = {
            VERSION: "4.3.1",
            PROTOCOL: 7,
            host: "ws.pusherapp.com",
            ws_port: 80,
            wss_port: 443,
            ws_path: "",
            sockjs_host: "sockjs.pusher.com",
            sockjs_http_port: 80,
            sockjs_https_port: 443,
            sockjs_path: "/pusher",
            stats_host: "stats.pusher.com",
            channel_auth_endpoint: "/pusher/auth",
            channel_auth_transport: "ajax",
            activity_timeout: 12e4,
            pong_timeout: 3e4,
            unavailable_timeout: 1e4,
            cdn_http: "http://js.pusher.com",
            cdn_https: "https://js.pusher.com",
            dependency_suffix: ".min"
        };
        e.__esModule = !0, e.default = n
    }, function(t, e, n) {
        "use strict";
        var r = n(4),
            i = n(2),
            o = function() {
                function t(t) {
                    this.options = t, this.receivers = t.receivers || r.ScriptReceivers, this.loading = {}
                }
                return t.prototype.load = function(t, e, n) {
                    var r = this;
                    if (r.loading[t] && r.loading[t].length > 0) r.loading[t].push(n);
                    else {
                        r.loading[t] = [n];
                        var o = i.default.createScriptRequest(r.getPath(t, e)),
                            s = r.receivers.create(function(e) {
                                if (r.receivers.remove(s), r.loading[t]) {
                                    var n = r.loading[t];
                                    delete r.loading[t];
                                    for (var i = function(t) {
                                            t || o.cleanup()
                                        }, a = 0; a < n.length; a++) n[a](e, i)
                                }
                            });
                        o.send(s)
                    }
                }, t.prototype.getRoot = function(t) {
                    var e, n = i.default.getDocument().location.protocol;
                    return e = t && t.useTLS || "https:" === n ? this.options.cdn_https : this.options.cdn_http, e.replace(/\/*$/, "") + "/" + this.options.version
                }, t.prototype.getPath = function(t, e) {
                    return this.getRoot(e) + "/" + t + this.options.suffix + ".js"
                }, t
            }();
        e.__esModule = !0, e.default = o
    }, function(t, e, n) {
        "use strict";
        var r = n(8),
            i = n(2),
            o = n(14),
            s = function(t, e, n) {
                var s, a = this;
                s = i.default.createXHR(), s.open("POST", a.options.authEndpoint, !0), s.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                for (var u in this.authOptions.headers) s.setRequestHeader(u, this.authOptions.headers[u]);
                return s.onreadystatechange = function() {
                    if (4 === s.readyState)
                        if (200 === s.status) {
                            var t, e = !1;
                            try {
                                t = JSON.parse(s.responseText), e = !0
                            } catch (t) {
                                n(!0, "JSON returned from webapp was invalid, yet status code was 200. Data was: " + s.responseText)
                            }
                            e && n(!1, t)
                        } else {
                            var i = o.default.buildLogSuffix("authenticationEndpoint");
                            r.default.warn("Couldn't retrieve authentication info. " + s.status + ("Clients must be authenticated to join private or presence channels. " + i)), n(!0, s.status)
                        }
                }, s.send(this.composeQuery(e)), s
            };
        e.__esModule = !0, e.default = s
    }, function(t, e, n) {
        "use strict";
        var r = n(9),
            i = n(1),
            o = {
                debug: function() {
                    for (var t = [], e = 0; e < arguments.length; e++) t[e - 0] = arguments[e];
                    i.default.log && i.default.log(r.stringify.apply(this, arguments))
                },
                warn: function() {
                    for (var t = [], e = 0; e < arguments.length; e++) t[e - 0] = arguments[e];
                    var n = r.stringify.apply(this, arguments);
                    i.default.log ? i.default.log(n) : window.console && (window.console.warn ? window.console.warn(n) : window.console.log && window.console.log(n))
                }
            };
        e.__esModule = !0, e.default = o
    }, function(t, e, n) {
        "use strict";

        function r(t) {
            for (var e = [], n = 1; n < arguments.length; n++) e[n - 1] = arguments[n];
            for (var i = 0; i < e.length; i++) {
                var o = e[i];
                for (var s in o) o[s] && o[s].constructor && o[s].constructor === Object ? t[s] = r(t[s] || {}, o[s]) : t[s] = o[s]
            }
            return t
        }

        function i() {
            for (var t = ["Pusher"], e = 0; e < arguments.length; e++) "string" == typeof arguments[e] ? t.push(arguments[e]) : t.push(w(arguments[e]));
            return t.join(" : ")
        }

        function o(t, e) {
            var n = Array.prototype.indexOf;
            if (null === t) return -1;
            if (n && t.indexOf === n) return t.indexOf(e);
            for (var r = 0, i = t.length; r < i; r++)
                if (t[r] === e) return r;
            return -1
        }

        function s(t, e) {
            for (var n in t) Object.prototype.hasOwnProperty.call(t, n) && e(t[n], n, t)
        }

        function a(t) {
            var e = [];
            return s(t, function(t, n) {
                e.push(n)
            }), e
        }

        function u(t) {
            var e = [];
            return s(t, function(t) {
                e.push(t)
            }), e
        }

        function c(t, e, n) {
            for (var r = 0; r < t.length; r++) e.call(n || window, t[r], r, t)
        }

        function h(t, e) {
            for (var n = [], r = 0; r < t.length; r++) n.push(e(t[r], r, t, n));
            return n
        }

        function f(t, e) {
            var n = {};
            return s(t, function(t, r) {
                n[r] = e(t)
            }), n
        }

        function l(t, e) {
            e = e || function(t) {
                return !!t
            };
            for (var n = [], r = 0; r < t.length; r++) e(t[r], r, t, n) && n.push(t[r]);
            return n
        }

        function p(t, e) {
            var n = {};
            return s(t, function(r, i) {
                (e && e(r, i, t, n) || Boolean(r)) && (n[i] = r)
            }), n
        }

        function d(t) {
            var e = [];
            return s(t, function(t, n) {
                e.push([n, t])
            }), e
        }

        function y(t, e) {
            for (var n = 0; n < t.length; n++)
                if (e(t[n], n, t)) return !0;
            return !1
        }

        function g(t, e) {
            for (var n = 0; n < t.length; n++)
                if (!e(t[n], n, t)) return !1;
            return !0
        }

        function v(t) {
            return f(t, function(t) {
                return "object" == typeof t && (t = w(t)), encodeURIComponent(_.default(t.toString()))
            })
        }

        function b(t) {
            var e = p(t, function(t) {
                    return void 0 !== t
                }),
                n = h(d(v(e)), S.default.method("join", "=")).join("&");
            return n
        }

        function m(t) {
            var e = [],
                n = [];
            return function t(r, i) {
                var o, s, a;
                switch (typeof r) {
                    case "object":
                        if (!r) return null;
                        for (o = 0; o < e.length; o += 1)
                            if (e[o] === r) return {
                                $ref: n[o]
                            };
                        if (e.push(r), n.push(i), "[object Array]" === Object.prototype.toString.apply(r))
                            for (a = [], o = 0; o < r.length; o += 1) a[o] = t(r[o], i + "[" + o + "]");
                        else {
                            a = {};
                            for (s in r) Object.prototype.hasOwnProperty.call(r, s) && (a[s] = t(r[s], i + "[" + JSON.stringify(s) + "]"))
                        }
                        return a;
                    case "number":
                    case "string":
                    case "boolean":
                        return r
                }
            }(t, "$")
        }

        function w(t) {
            try {
                return JSON.stringify(t)
            } catch (e) {
                return JSON.stringify(m(t))
            }
        }
        var _ = n(10),
            S = n(11);
        e.extend = r, e.stringify = i, e.arrayIndexOf = o, e.objectApply = s, e.keys = a, e.values = u, e.apply = c, e.map = h, e.mapObject = f, e.filter = l, e.filterObject = p, e.flatten = d, e.any = y, e.all = g, e.encodeParamsObject = v, e.buildQueryString = b, e.decycleObject = m, e.safeJSONStringify = w
    }, function(t, e, n) {
        "use strict";

        function r(t) {
            return l(h(t))
        }
        e.__esModule = !0, e.default = r;
        for (var i = String.fromCharCode, o = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", s = {}, a = 0, u = o.length; a < u; a++) s[o.charAt(a)] = a;
        var c = function(t) {
                var e = t.charCodeAt(0);
                return e < 128 ? t : e < 2048 ? i(192 | e >>> 6) + i(128 | 63 & e) : i(224 | e >>> 12 & 15) + i(128 | e >>> 6 & 63) + i(128 | 63 & e)
            },
            h = function(t) {
                return t.replace(/[^\x00-\x7F]/g, c)
            },
            f = function(t) {
                var e = [0, 2, 1][t.length % 3],
                    n = t.charCodeAt(0) << 16 | (t.length > 1 ? t.charCodeAt(1) : 0) << 8 | (t.length > 2 ? t.charCodeAt(2) : 0),
                    r = [o.charAt(n >>> 18), o.charAt(n >>> 12 & 63), e >= 2 ? "=" : o.charAt(n >>> 6 & 63), e >= 1 ? "=" : o.charAt(63 & n)];
                return r.join("")
            },
            l = window.btoa || function(t) {
                return t.replace(/[\s\S]{1,3}/g, f)
            }
    }, function(t, e, n) {
        "use strict";
        var r = n(12),
            i = {
                now: function() {
                    return Date.now ? Date.now() : (new Date).valueOf()
                },
                defer: function(t) {
                    return new r.OneOffTimer(0, t)
                },
                method: function(t) {
                    for (var e = [], n = 1; n < arguments.length; n++) e[n - 1] = arguments[n];
                    var r = Array.prototype.slice.call(arguments, 1);
                    return function(e) {
                        return e[t].apply(e, r.concat(arguments))
                    }
                }
            };
        e.__esModule = !0, e.default = i
    }, function(t, e, n) {
        "use strict";

        function r(t) {
            window.clearTimeout(t)
        }

        function i(t) {
            window.clearInterval(t)
        }
        var o = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            s = n(13),
            a = function(t) {
                function e(e, n) {
                    t.call(this, setTimeout, r, e, function(t) {
                        return n(), null
                    })
                }
                return o(e, t), e
            }(s.default);
        e.OneOffTimer = a;
        var u = function(t) {
            function e(e, n) {
                t.call(this, setInterval, i, e, function(t) {
                    return n(), t
                })
            }
            return o(e, t), e
        }(s.default);
        e.PeriodicTimer = u
    }, function(t, e) {
        "use strict";
        var n = function() {
            function t(t, e, n, r) {
                var i = this;
                this.clear = e, this.timer = t(function() {
                    i.timer && (i.timer = r(i.timer))
                }, n)
            }
            return t.prototype.isRunning = function() {
                return null !== this.timer
            }, t.prototype.ensureAborted = function() {
                this.timer && (this.clear(this.timer), this.timer = null)
            }, t
        }();
        e.__esModule = !0, e.default = n
    }, function(t, e) {
        "use strict";
        var n = {
                baseUrl: "https://pusher.com",
                urls: {
                    authenticationEndpoint: {
                        path: "/docs/authenticating_users"
                    },
                    javascriptQuickStart: {
                        path: "/docs/javascript_quick_start"
                    }
                }
            },
            r = function(t) {
                var e = "See:",
                    r = n.urls[t];
                if (!r) return "";
                var i;
                return r.fullUrl ? i = r.fullUrl : r.path && (i = n.baseUrl + r.path), i ? e + " " + i : ""
            };
        e.__esModule = !0, e.default = {
            buildLogSuffix: r
        }
    }, function(t, e, n) {
        "use strict";
        var r = n(8),
            i = function(t, e, n) {
                void 0 !== this.authOptions.headers && r.default.warn("Warn", "To send headers with the auth request, you must use AJAX, rather than JSONP.");
                var i = t.nextAuthCallbackID.toString();
                t.nextAuthCallbackID++;
                var o = t.getDocument(),
                    s = o.createElement("script");
                t.auth_callbacks[i] = function(t) {
                    n(!1, t)
                };
                var a = "Pusher.auth_callbacks['" + i + "']";
                s.src = this.options.authEndpoint + "?callback=" + encodeURIComponent(a) + "&" + this.composeQuery(e);
                var u = o.getElementsByTagName("head")[0] || o.documentElement;
                u.insertBefore(s, u.firstChild)
            };
        e.__esModule = !0, e.default = i
    }, function(t, e) {
        "use strict";
        var n = function() {
            function t(t) {
                this.src = t
            }
            return t.prototype.send = function(t) {
                var e = this,
                    n = "Error loading " + e.src;
                e.script = document.createElement("script"), e.script.id = t.id, e.script.src = e.src, e.script.type = "text/javascript", e.script.charset = "UTF-8", e.script.addEventListener ? (e.script.onerror = function() {
                    t.callback(n)
                }, e.script.onload = function() {
                    t.callback(null)
                }) : e.script.onreadystatechange = function() {
                    "loaded" !== e.script.readyState && "complete" !== e.script.readyState || t.callback(null)
                }, void 0 === e.script.async && document.attachEvent && /opera/i.test(navigator.userAgent) ? (e.errorScript = document.createElement("script"), e.errorScript.id = t.id + "_error", e.errorScript.text = t.name + "('" + n + "');", e.script.async = e.errorScript.async = !1) : e.script.async = !0;
                var r = document.getElementsByTagName("head")[0];
                r.insertBefore(e.script, r.firstChild), e.errorScript && r.insertBefore(e.errorScript, e.script.nextSibling)
            }, t.prototype.cleanup = function() {
                this.script && (this.script.onload = this.script.onerror = null, this.script.onreadystatechange = null), this.script && this.script.parentNode && this.script.parentNode.removeChild(this.script), this.errorScript && this.errorScript.parentNode && this.errorScript.parentNode.removeChild(this.errorScript), this.script = null, this.errorScript = null
            }, t
        }();
        e.__esModule = !0, e.default = n
    }, function(t, e, n) {
        "use strict";
        var r = n(9),
            i = n(2),
            o = function() {
                function t(t, e) {
                    this.url = t, this.data = e
                }
                return t.prototype.send = function(t) {
                    if (!this.request) {
                        var e = r.buildQueryString(this.data),
                            n = this.url + "/" + t.number + "?" + e;
                        this.request = i.default.createScriptRequest(n), this.request.send(t)
                    }
                }, t.prototype.cleanup = function() {
                    this.request && this.request.cleanup()
                }, t
            }();
        e.__esModule = !0, e.default = o
    }, function(t, e, n) {
        "use strict";
        var r = n(2),
            i = n(4),
            o = function(t, e) {
                return function(n, o) {
                    var s = "http" + (e ? "s" : "") + "://",
                        a = s + (t.host || t.options.host) + t.options.path,
                        u = r.default.createJSONPRequest(a, n),
                        c = r.default.ScriptReceivers.create(function(e, n) {
                            i.ScriptReceivers.remove(c), u.cleanup(), n && n.host && (t.host = n.host), o && o(e, n)
                        });
                    u.send(c)
                }
            },
            s = {
                name: "jsonp",
                getAgent: o
            };
        e.__esModule = !0, e.default = s
    }, function(t, e, n) {
        "use strict";
        var r = n(20),
            i = n(22),
            o = n(21),
            s = n(2),
            a = n(3),
            u = n(9),
            c = new i.default({
                file: "sockjs",
                urls: o.sockjs,
                handlesActivityChecks: !0,
                supportsPing: !1,
                isSupported: function() {
                    return !0
                },
                isInitialized: function() {
                    return void 0 !== window.SockJS
                },
                getSocket: function(t, e) {
                    return new window.SockJS(t, null, {
                        js_path: a.Dependencies.getPath("sockjs", {
                            useTLS: e.useTLS
                        }),
                        ignore_null_origin: e.ignoreNullOrigin
                    })
                },
                beforeOpen: function(t, e) {
                    t.send(JSON.stringify({
                        path: e
                    }))
                }
            }),
            h = {
                isSupported: function(t) {
                    var e = s.default.isXDRSupported(t.useTLS);
                    return e
                }
            },
            f = new i.default(u.extend({}, r.streamingConfiguration, h)),
            l = new i.default(u.extend({}, r.pollingConfiguration, h));
        r.default.xdr_streaming = f, r.default.xdr_polling = l, r.default.sockjs = c, e.__esModule = !0, e.default = r.default
    }, function(t, e, n) {
        "use strict";
        var r = n(21),
            i = n(22),
            o = n(9),
            s = n(2),
            a = new i.default({
                urls: r.ws,
                handlesActivityChecks: !1,
                supportsPing: !1,
                isInitialized: function() {
                    return Boolean(s.default.getWebSocketAPI())
                },
                isSupported: function() {
                    return Boolean(s.default.getWebSocketAPI())
                },
                getSocket: function(t) {
                    return s.default.createWebSocket(t)
                }
            }),
            u = {
                urls: r.http,
                handlesActivityChecks: !1,
                supportsPing: !0,
                isInitialized: function() {
                    return !0
                }
            };
        e.streamingConfiguration = o.extend({
            getSocket: function(t) {
                return s.default.HTTPFactory.createStreamingSocket(t)
            }
        }, u), e.pollingConfiguration = o.extend({
            getSocket: function(t) {
                return s.default.HTTPFactory.createPollingSocket(t)
            }
        }, u);
        var c = {
                isSupported: function() {
                    return s.default.isXHRSupported()
                }
            },
            h = new i.default(o.extend({}, e.streamingConfiguration, c)),
            f = new i.default(o.extend({}, e.pollingConfiguration, c)),
            l = {
                ws: a,
                xhr_streaming: h,
                xhr_polling: f
            };
        e.__esModule = !0, e.default = l
    }, function(t, e, n) {
        "use strict";

        function r(t, e, n) {
            var r = t + (e.useTLS ? "s" : ""),
                i = e.useTLS ? e.hostTLS : e.hostNonTLS;
            return r + "://" + i + n
        }

        function i(t, e) {
            var n = "/app/" + t,
                r = "?protocol=" + o.default.PROTOCOL + "&client=js&version=" + o.default.VERSION + (e ? "&" + e : "");
            return n + r
        }
        var o = n(5);
        e.ws = {
            getInitial: function(t, e) {
                var n = (e.httpPath || "") + i(t, "flash=false");
                return r("ws", e, n)
            }
        }, e.http = {
            getInitial: function(t, e) {
                var n = (e.httpPath || "/pusher") + i(t);
                return r("http", e, n)
            }
        }, e.sockjs = {
            getInitial: function(t, e) {
                return r("http", e, e.httpPath || "/pusher")
            },
            getPath: function(t, e) {
                return i(t)
            }
        }
    }, function(t, e, n) {
        "use strict";
        var r = n(23),
            i = function() {
                function t(t) {
                    this.hooks = t
                }
                return t.prototype.isSupported = function(t) {
                    return this.hooks.isSupported(t)
                }, t.prototype.createConnection = function(t, e, n, i) {
                    return new r.default(this.hooks, t, e, n, i)
                }, t
            }();
        e.__esModule = !0, e.default = i
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(11),
            o = n(9),
            s = n(24),
            a = n(8),
            u = n(2),
            c = function(t) {
                function e(e, n, r, i, o) {
                    t.call(this), this.initialize = u.default.transportConnectionInitializer, this.hooks = e, this.name = n, this.priority = r, this.key = i, this.options = o, this.state = "new", this.timeline = o.timeline, this.activityTimeout = o.activityTimeout, this.id = this.timeline.generateUniqueID()
                }
                return r(e, t), e.prototype.handlesActivityChecks = function() {
                    return Boolean(this.hooks.handlesActivityChecks)
                }, e.prototype.supportsPing = function() {
                    return Boolean(this.hooks.supportsPing)
                }, e.prototype.connect = function() {
                    var t = this;
                    if (this.socket || "initialized" !== this.state) return !1;
                    var e = this.hooks.urls.getInitial(this.key, this.options);
                    try {
                        this.socket = this.hooks.getSocket(e, this.options)
                    } catch (e) {
                        return i.default.defer(function() {
                            t.onError(e), t.changeState("closed")
                        }), !1
                    }
                    return this.bindListeners(), a.default.debug("Connecting", {
                        transport: this.name,
                        url: e
                    }), this.changeState("connecting"), !0
                }, e.prototype.close = function() {
                    return !!this.socket && (this.socket.close(), !0)
                }, e.prototype.send = function(t) {
                    var e = this;
                    return "open" === this.state && (i.default.defer(function() {
                        e.socket && e.socket.send(t)
                    }), !0)
                }, e.prototype.ping = function() {
                    "open" === this.state && this.supportsPing() && this.socket.ping()
                }, e.prototype.onOpen = function() {
                    this.hooks.beforeOpen && this.hooks.beforeOpen(this.socket, this.hooks.urls.getPath(this.key, this.options)), this.changeState("open"), this.socket.onopen = void 0
                }, e.prototype.onError = function(t) {
                    this.emit("error", {
                        type: "WebSocketError",
                        error: t
                    }), this.timeline.error(this.buildTimelineMessage({
                        error: t.toString()
                    }))
                }, e.prototype.onClose = function(t) {
                    t ? this.changeState("closed", {
                        code: t.code,
                        reason: t.reason,
                        wasClean: t.wasClean
                    }) : this.changeState("closed"), this.unbindListeners(), this.socket = void 0
                }, e.prototype.onMessage = function(t) {
                    this.emit("message", t)
                }, e.prototype.onActivity = function() {
                    this.emit("activity")
                }, e.prototype.bindListeners = function() {
                    var t = this;
                    this.socket.onopen = function() {
                        t.onOpen()
                    }, this.socket.onerror = function(e) {
                        t.onError(e)
                    }, this.socket.onclose = function(e) {
                        t.onClose(e)
                    }, this.socket.onmessage = function(e) {
                        t.onMessage(e)
                    }, this.supportsPing() && (this.socket.onactivity = function() {
                        t.onActivity()
                    })
                }, e.prototype.unbindListeners = function() {
                    this.socket && (this.socket.onopen = void 0, this.socket.onerror = void 0, this.socket.onclose = void 0, this.socket.onmessage = void 0, this.supportsPing() && (this.socket.onactivity = void 0))
                }, e.prototype.changeState = function(t, e) {
                    this.state = t, this.timeline.info(this.buildTimelineMessage({
                        state: t,
                        params: e
                    })), this.emit(t, e)
                }, e.prototype.buildTimelineMessage = function(t) {
                    return o.extend({
                        cid: this.id
                    }, t)
                }, e
            }(s.default);
        e.__esModule = !0, e.default = c
    }, function(t, e, n) {
        "use strict";
        var r = n(9),
            i = n(25),
            o = function() {
                function t(t) {
                    this.callbacks = new i.default, this.global_callbacks = [], this.failThrough = t
                }
                return t.prototype.bind = function(t, e, n) {
                    return this.callbacks.add(t, e, n), this
                }, t.prototype.bind_global = function(t) {
                    return this.global_callbacks.push(t), this
                }, t.prototype.unbind = function(t, e, n) {
                    return this.callbacks.remove(t, e, n), this
                }, t.prototype.unbind_global = function(t) {
                    return t ? (this.global_callbacks = r.filter(this.global_callbacks || [], function(e) {
                        return e !== t
                    }), this) : (this.global_callbacks = [], this)
                }, t.prototype.unbind_all = function() {
                    return this.unbind(), this.unbind_global(), this
                }, t.prototype.emit = function(t, e) {
                    var n;
                    for (n = 0; n < this.global_callbacks.length; n++) this.global_callbacks[n](t, e);
                    var r = this.callbacks.get(t);
                    if (r && r.length > 0)
                        for (n = 0; n < r.length; n++) r[n].fn.call(r[n].context || window, e);
                    else this.failThrough && this.failThrough(t, e);
                    return this
                }, t
            }();
        e.__esModule = !0, e.default = o
    }, function(t, e, n) {
        "use strict";

        function r(t) {
            return "_" + t
        }
        var i = n(9),
            o = function() {
                function t() {
                    this._callbacks = {}
                }
                return t.prototype.get = function(t) {
                    return this._callbacks[r(t)]
                }, t.prototype.add = function(t, e, n) {
                    var i = r(t);
                    this._callbacks[i] = this._callbacks[i] || [], this._callbacks[i].push({
                        fn: e,
                        context: n
                    })
                }, t.prototype.remove = function(t, e, n) {
                    if (!t && !e && !n) return void(this._callbacks = {});
                    var o = t ? [r(t)] : i.keys(this._callbacks);
                    e || n ? this.removeCallback(o, e, n) : this.removeAllCallbacks(o)
                }, t.prototype.removeCallback = function(t, e, n) {
                    i.apply(t, function(t) {
                        this._callbacks[t] = i.filter(this._callbacks[t] || [], function(t) {
                            return e && e !== t.fn || n && n !== t.context
                        }), 0 === this._callbacks[t].length && delete this._callbacks[t]
                    }, this)
                }, t.prototype.removeAllCallbacks = function(t) {
                    i.apply(t, function(t) {
                        delete this._callbacks[t]
                    }, this)
                }, t
            }();
        e.__esModule = !0, e.default = o
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(24),
            o = function(t) {
                function e() {
                    t.call(this);
                    var e = this;
                    void 0 !== window.addEventListener && (window.addEventListener("online", function() {
                        e.emit("online")
                    }, !1), window.addEventListener("offline", function() {
                        e.emit("offline")
                    }, !1))
                }
                return r(e, t), e.prototype.isOnline = function() {
                    return void 0 === window.navigator.onLine || window.navigator.onLine
                }, e
            }(i.default);
        e.NetInfo = o, e.Network = new o
    }, function(t, e) {
        "use strict";
        var n = function(t) {
            var e;
            return e = t.useTLS ? [":best_connected_ever", ":ws_loop", [":delayed", 2e3, [":http_fallback_loop"]]] : [":best_connected_ever", ":ws_loop", [":delayed", 2e3, [":wss_loop"]],
                [":delayed", 5e3, [":http_fallback_loop"]]
            ], [
                [":def", "ws_options", {
                    hostNonTLS: t.wsHost + ":" + t.wsPort,
                    hostTLS: t.wsHost + ":" + t.wssPort,
                    httpPath: t.wsPath
                }],
                [":def", "wss_options", [":extend", ":ws_options", {
                    useTLS: !0
                }]],
                [":def", "sockjs_options", {
                    hostNonTLS: t.httpHost + ":" + t.httpPort,
                    hostTLS: t.httpHost + ":" + t.httpsPort,
                    httpPath: t.httpPath
                }],
                [":def", "timeouts", {
                    loop: !0,
                    timeout: 15e3,
                    timeoutLimit: 6e4
                }],
                [":def", "ws_manager", [":transport_manager", {
                    lives: 2,
                    minPingDelay: 1e4,
                    maxPingDelay: t.activity_timeout
                }]],
                [":def", "streaming_manager", [":transport_manager", {
                    lives: 2,
                    minPingDelay: 1e4,
                    maxPingDelay: t.activity_timeout
                }]],
                [":def_transport", "ws", "ws", 3, ":ws_options", ":ws_manager"],
                [":def_transport", "wss", "ws", 3, ":wss_options", ":ws_manager"],
                [":def_transport", "sockjs", "sockjs", 1, ":sockjs_options"],
                [":def_transport", "xhr_streaming", "xhr_streaming", 1, ":sockjs_options", ":streaming_manager"],
                [":def_transport", "xdr_streaming", "xdr_streaming", 1, ":sockjs_options", ":streaming_manager"],
                [":def_transport", "xhr_polling", "xhr_polling", 1, ":sockjs_options"],
                [":def_transport", "xdr_polling", "xdr_polling", 1, ":sockjs_options"],
                [":def", "ws_loop", [":sequential", ":timeouts", ":ws"]],
                [":def", "wss_loop", [":sequential", ":timeouts", ":wss"]],
                [":def", "sockjs_loop", [":sequential", ":timeouts", ":sockjs"]],
                [":def", "streaming_loop", [":sequential", ":timeouts", [":if", [":is_supported", ":xhr_streaming"], ":xhr_streaming", ":xdr_streaming"]]],
                [":def", "polling_loop", [":sequential", ":timeouts", [":if", [":is_supported", ":xhr_polling"], ":xhr_polling", ":xdr_polling"]]],
                [":def", "http_loop", [":if", [":is_supported", ":streaming_loop"],
                    [":best_connected_ever", ":streaming_loop", [":delayed", 4e3, [":polling_loop"]]],
                    [":polling_loop"]
                ]],
                [":def", "http_fallback_loop", [":if", [":is_supported", ":http_loop"],
                    [":http_loop"],
                    [":sockjs_loop"]
                ]],
                [":def", "strategy", [":cached", 18e5, [":first_connected", [":if", [":is_supported", ":ws"], e, ":http_fallback_loop"]]]]
            ]
        };
        e.__esModule = !0, e.default = n
    }, function(t, e, n) {
        "use strict";

        function r() {
            var t = this;
            t.timeline.info(t.buildTimelineMessage({
                transport: t.name + (t.options.useTLS ? "s" : "")
            })), t.hooks.isInitialized() ? t.changeState("initialized") : t.hooks.file ? (t.changeState("initializing"), i.Dependencies.load(t.hooks.file, {
                useTLS: t.options.useTLS
            }, function(e, n) {
                t.hooks.isInitialized() ? (t.changeState("initialized"), n(!0)) : (e && t.onError(e), t.onClose(), n(!1))
            })) : t.onClose()
        }
        var i = n(3);
        e.__esModule = !0, e.default = r
    }, function(t, e, n) {
        "use strict";
        var r = n(30),
            i = n(32);
        i.default.createXDR = function(t, e) {
            return this.createRequest(r.default, t, e)
        }, e.__esModule = !0, e.default = i.default
    }, function(t, e, n) {
        "use strict";
        var r = n(31),
            i = {
                getRequest: function(t) {
                    var e = new window.XDomainRequest;
                    return e.ontimeout = function() {
                        t.emit("error", new r.RequestTimedOut), t.close()
                    }, e.onerror = function(e) {
                        t.emit("error", e), t.close()
                    }, e.onprogress = function() {
                        e.responseText && e.responseText.length > 0 && t.onChunk(200, e.responseText)
                    }, e.onload = function() {
                        e.responseText && e.responseText.length > 0 && t.onChunk(200, e.responseText), t.emit("finished", 200), t.close()
                    }, e
                },
                abortRequest: function(t) {
                    t.ontimeout = t.onerror = t.onprogress = t.onload = null, t.abort()
                }
            };
        e.__esModule = !0, e.default = i
    }, function(t, e) {
        "use strict";
        var n = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            r = function(t) {
                function e() {
                    t.apply(this, arguments)
                }
                return n(e, t), e
            }(Error);
        e.BadEventName = r;
        var i = function(t) {
            function e() {
                t.apply(this, arguments)
            }
            return n(e, t), e
        }(Error);
        e.RequestTimedOut = i;
        var o = function(t) {
            function e() {
                t.apply(this, arguments)
            }
            return n(e, t), e
        }(Error);
        e.TransportPriorityTooLow = o;
        var s = function(t) {
            function e() {
                t.apply(this, arguments)
            }
            return n(e, t), e
        }(Error);
        e.TransportClosed = s;
        var a = function(t) {
            function e() {
                t.apply(this, arguments)
            }
            return n(e, t), e
        }(Error);
        e.UnsupportedFeature = a;
        var u = function(t) {
            function e() {
                t.apply(this, arguments)
            }
            return n(e, t), e
        }(Error);
        e.UnsupportedTransport = u;
        var c = function(t) {
            function e() {
                t.apply(this, arguments)
            }
            return n(e, t), e
        }(Error);
        e.UnsupportedStrategy = c
    }, function(t, e, n) {
        "use strict";
        var r = n(33),
            i = n(34),
            o = n(36),
            s = n(37),
            a = n(38),
            u = {
                createStreamingSocket: function(t) {
                    return this.createSocket(o.default, t)
                },
                createPollingSocket: function(t) {
                    return this.createSocket(s.default, t)
                },
                createSocket: function(t, e) {
                    return new i.default(t, e)
                },
                createXHR: function(t, e) {
                    return this.createRequest(a.default, t, e)
                },
                createRequest: function(t, e, n) {
                    return new r.default(t, e, n)
                }
            };
        e.__esModule = !0, e.default = u
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(2),
            o = n(24),
            s = 262144,
            a = function(t) {
                function e(e, n, r) {
                    t.call(this), this.hooks = e, this.method = n, this.url = r
                }
                return r(e, t), e.prototype.start = function(t) {
                    var e = this;
                    this.position = 0, this.xhr = this.hooks.getRequest(this), this.unloader = function() {
                        e.close()
                    }, i.default.addUnloadListener(this.unloader), this.xhr.open(this.method, this.url, !0), this.xhr.setRequestHeader && this.xhr.setRequestHeader("Content-Type", "application/json"), this.xhr.send(t)
                }, e.prototype.close = function() {
                    this.unloader && (i.default.removeUnloadListener(this.unloader), this.unloader = null), this.xhr && (this.hooks.abortRequest(this.xhr), this.xhr = null)
                }, e.prototype.onChunk = function(t, e) {
                    for (;;) {
                        var n = this.advanceBuffer(e);
                        if (!n) break;
                        this.emit("chunk", {
                            status: t,
                            data: n
                        })
                    }
                    this.isBufferTooLong(e) && this.emit("buffer_too_long")
                }, e.prototype.advanceBuffer = function(t) {
                    var e = t.slice(this.position),
                        n = e.indexOf("\n");
                    return n !== -1 ? (this.position += n + 1, e.slice(0, n)) : null
                }, e.prototype.isBufferTooLong = function(t) {
                    return this.position === t.length && t.length > s
                }, e
            }(o.default);
        e.__esModule = !0, e.default = a
    }, function(t, e, n) {
        "use strict";

        function r(t) {
            var e = /([^\?]*)\/*(\??.*)/.exec(t);
            return {

                base: e[1],
                queryString: e[2]
            }
        }

        function i(t, e) {
            return t.base + "/" + e + "/xhr_send"
        }

        function o(t) {
            var e = t.indexOf("?") === -1 ? "?" : "&";
            return t + e + "t=" + +new Date + "&n=" + l++
        }

        function s(t, e) {
            var n = /(https?:\/\/)([^\/:]+)((\/|:)?.*)/.exec(t);
            return n[1] + e + n[3]
        }

        function a(t) {
            return Math.floor(Math.random() * t)
        }

        function u(t) {
            for (var e = [], n = 0; n < t; n++) e.push(a(32).toString(32));
            return e.join("")
        }
        var c = n(35),
            h = n(11),
            f = n(2),
            l = 1,
            p = function() {
                function t(t, e) {
                    this.hooks = t, this.session = a(1e3) + "/" + u(8), this.location = r(e), this.readyState = c.default.CONNECTING, this.openStream()
                }
                return t.prototype.send = function(t) {
                    return this.sendRaw(JSON.stringify([t]))
                }, t.prototype.ping = function() {
                    this.hooks.sendHeartbeat(this)
                }, t.prototype.close = function(t, e) {
                    this.onClose(t, e, !0)
                }, t.prototype.sendRaw = function(t) {
                    if (this.readyState !== c.default.OPEN) return !1;
                    try {
                        return f.default.createSocketRequest("POST", o(i(this.location, this.session))).start(t), !0
                    } catch (t) {
                        return !1
                    }
                }, t.prototype.reconnect = function() {
                    this.closeStream(), this.openStream()
                }, t.prototype.onClose = function(t, e, n) {
                    this.closeStream(), this.readyState = c.default.CLOSED, this.onclose && this.onclose({
                        code: t,
                        reason: e,
                        wasClean: n
                    })
                }, t.prototype.onChunk = function(t) {
                    if (200 === t.status) {
                        this.readyState === c.default.OPEN && this.onActivity();
                        var e, n = t.data.slice(0, 1);
                        switch (n) {
                            case "o":
                                e = JSON.parse(t.data.slice(1) || "{}"), this.onOpen(e);
                                break;
                            case "a":
                                e = JSON.parse(t.data.slice(1) || "[]");
                                for (var r = 0; r < e.length; r++) this.onEvent(e[r]);
                                break;
                            case "m":
                                e = JSON.parse(t.data.slice(1) || "null"), this.onEvent(e);
                                break;
                            case "h":
                                this.hooks.onHeartbeat(this);
                                break;
                            case "c":
                                e = JSON.parse(t.data.slice(1) || "[]"), this.onClose(e[0], e[1], !0)
                        }
                    }
                }, t.prototype.onOpen = function(t) {
                    this.readyState === c.default.CONNECTING ? (t && t.hostname && (this.location.base = s(this.location.base, t.hostname)), this.readyState = c.default.OPEN, this.onopen && this.onopen()) : this.onClose(1006, "Server lost session", !0)
                }, t.prototype.onEvent = function(t) {
                    this.readyState === c.default.OPEN && this.onmessage && this.onmessage({
                        data: t
                    })
                }, t.prototype.onActivity = function() {
                    this.onactivity && this.onactivity()
                }, t.prototype.onError = function(t) {
                    this.onerror && this.onerror(t)
                }, t.prototype.openStream = function() {
                    var t = this;
                    this.stream = f.default.createSocketRequest("POST", o(this.hooks.getReceiveURL(this.location, this.session))), this.stream.bind("chunk", function(e) {
                        t.onChunk(e)
                    }), this.stream.bind("finished", function(e) {
                        t.hooks.onFinished(t, e)
                    }), this.stream.bind("buffer_too_long", function() {
                        t.reconnect()
                    });
                    try {
                        this.stream.start()
                    } catch (e) {
                        h.default.defer(function() {
                            t.onError(e), t.onClose(1006, "Could not start streaming", !1)
                        })
                    }
                }, t.prototype.closeStream = function() {
                    this.stream && (this.stream.unbind_all(), this.stream.close(), this.stream = null)
                }, t
            }();
        e.__esModule = !0, e.default = p
    }, function(t, e) {
        "use strict";
        var n;
        ! function(t) {
            t[t.CONNECTING = 0] = "CONNECTING", t[t.OPEN = 1] = "OPEN", t[t.CLOSED = 3] = "CLOSED"
        }(n || (n = {})), e.__esModule = !0, e.default = n
    }, function(t, e) {
        "use strict";
        var n = {
            getReceiveURL: function(t, e) {
                return t.base + "/" + e + "/xhr_streaming" + t.queryString
            },
            onHeartbeat: function(t) {
                t.sendRaw("[]")
            },
            sendHeartbeat: function(t) {
                t.sendRaw("[]")
            },
            onFinished: function(t, e) {
                t.onClose(1006, "Connection interrupted (" + e + ")", !1)
            }
        };
        e.__esModule = !0, e.default = n
    }, function(t, e) {
        "use strict";
        var n = {
            getReceiveURL: function(t, e) {
                return t.base + "/" + e + "/xhr" + t.queryString
            },
            onHeartbeat: function() {},
            sendHeartbeat: function(t) {
                t.sendRaw("[]")
            },
            onFinished: function(t, e) {
                200 === e ? t.reconnect() : t.onClose(1006, "Connection interrupted (" + e + ")", !1)
            }
        };
        e.__esModule = !0, e.default = n
    }, function(t, e, n) {
        "use strict";
        var r = n(2),
            i = {
                getRequest: function(t) {
                    var e = r.default.getXHRAPI(),
                        n = new e;
                    return n.onreadystatechange = n.onprogress = function() {
                        switch (n.readyState) {
                            case 3:
                                n.responseText && n.responseText.length > 0 && t.onChunk(n.status, n.responseText);
                                break;
                            case 4:
                                n.responseText && n.responseText.length > 0 && t.onChunk(n.status, n.responseText), t.emit("finished", n.status), t.close()
                        }
                    }, n
                },
                abortRequest: function(t) {
                    t.onreadystatechange = null, t.abort()
                }
            };
        e.__esModule = !0, e.default = i
    }, function(t, e, n) {
        "use strict";
        var r = n(9),
            i = n(11),
            o = n(40),
            s = function() {
                function t(t, e, n) {
                    this.key = t, this.session = e, this.events = [], this.options = n || {}, this.sent = 0, this.uniqueID = 0
                }
                return t.prototype.log = function(t, e) {
                    t <= this.options.level && (this.events.push(r.extend({}, e, {
                        timestamp: i.default.now()
                    })), this.options.limit && this.events.length > this.options.limit && this.events.shift())
                }, t.prototype.error = function(t) {
                    this.log(o.default.ERROR, t)
                }, t.prototype.info = function(t) {
                    this.log(o.default.INFO, t)
                }, t.prototype.debug = function(t) {
                    this.log(o.default.DEBUG, t)
                }, t.prototype.isEmpty = function() {
                    return 0 === this.events.length
                }, t.prototype.send = function(t, e) {
                    var n = this,
                        i = r.extend({
                            session: this.session,
                            bundle: this.sent + 1,
                            key: this.key,
                            lib: "js",
                            version: this.options.version,
                            cluster: this.options.cluster,
                            features: this.options.features,
                            timeline: this.events
                        }, this.options.params);
                    return this.events = [], t(i, function(t, r) {
                        t || n.sent++, e && e(t, r)
                    }), !0
                }, t.prototype.generateUniqueID = function() {
                    return this.uniqueID++, this.uniqueID
                }, t
            }();
        e.__esModule = !0, e.default = s
    }, function(t, e) {
        "use strict";
        var n;
        ! function(t) {
            t[t.ERROR = 3] = "ERROR", t[t.INFO = 6] = "INFO", t[t.DEBUG = 7] = "DEBUG"
        }(n || (n = {})), e.__esModule = !0, e.default = n
    }, function(t, e, n) {
        "use strict";

        function r(t) {
            return function(e) {
                return [t.apply(this, arguments), e]
            }
        }

        function i(t) {
            return "string" == typeof t && ":" === t.charAt(0)
        }

        function o(t, e) {
            return e[t.slice(1)]
        }

        function s(t, e) {
            if (0 === t.length) return [
                [], e
            ];
            var n = c(t[0], e),
                r = s(t.slice(1), n[1]);
            return [
                [n[0]].concat(r[0]), r[1]
            ]
        }

        function a(t, e) {
            if (!i(t)) return [t, e];
            var n = o(t, e);
            if (void 0 === n) throw "Undefined symbol " + t;
            return [n, e]
        }

        function u(t, e) {
            if (i(t[0])) {
                var n = o(t[0], e);
                if (t.length > 1) {
                    if ("function" != typeof n) throw "Calling non-function " + t[0];
                    var r = [h.extend({}, e)].concat(h.map(t.slice(1), function(t) {
                        return c(t, h.extend({}, e))[0]
                    }));
                    return n.apply(this, r)
                }
                return [n, e]
            }
            return s(t, e)
        }

        function c(t, e) {
            return "string" == typeof t ? a(t, e) : "object" == typeof t && t instanceof Array && t.length > 0 ? u(t, e) : [t, e]
        }
        var h = n(9),
            f = n(11),
            l = n(42),
            p = n(31),
            d = n(64),
            y = n(65),
            g = n(66),
            v = n(67),
            b = n(68),
            m = n(69),
            w = n(70),
            _ = n(2),
            S = _.default.Transports;
        e.build = function(t, e) {
            var n = h.extend({}, T, e);
            return c(t, n)[1].strategy
        };
        var k = {
                isSupported: function() {
                    return !1
                },
                connect: function(t, e) {
                    var n = f.default.defer(function() {
                        e(new p.UnsupportedStrategy)
                    });
                    return {
                        abort: function() {
                            n.ensureAborted()
                        },
                        forceMinPriority: function() {}
                    }
                }
            },
            T = {
                extend: function(t, e, n) {
                    return [h.extend({}, e, n), t]
                },
                def: function(t, e, n) {
                    if (void 0 !== t[e]) throw "Redefining symbol " + e;
                    return t[e] = n, [void 0, t]
                },
                def_transport: function(t, e, n, r, i, o) {
                    var s = S[n];
                    if (!s) throw new p.UnsupportedTransport(n);
                    var a, u = !(t.enabledTransports && h.arrayIndexOf(t.enabledTransports, e) === -1 || t.disabledTransports && h.arrayIndexOf(t.disabledTransports, e) !== -1);
                    a = u ? new d.default(e, r, o ? o.getAssistant(s) : s, h.extend({
                        key: t.key,
                        useTLS: t.useTLS,
                        timeline: t.timeline,
                        ignoreNullOrigin: t.ignoreNullOrigin
                    }, i)) : k;
                    var c = t.def(t, e, a)[1];
                    return c.Transports = t.Transports || {}, c.Transports[e] = a, [void 0, c]
                },
                transport_manager: r(function(t, e) {
                    return new l.default(e)
                }),
                sequential: r(function(t, e) {
                    var n = Array.prototype.slice.call(arguments, 2);
                    return new y.default(n, e)
                }),
                cached: r(function(t, e, n) {
                    return new v.default(n, t.Transports, {
                        ttl: e,
                        timeline: t.timeline,
                        useTLS: t.useTLS
                    })
                }),
                first_connected: r(function(t, e) {
                    return new w.default(e)
                }),
                best_connected_ever: r(function() {
                    var t = Array.prototype.slice.call(arguments, 1);
                    return new g.default(t)
                }),
                delayed: r(function(t, e, n) {
                    return new b.default(n, {
                        delay: e
                    })
                }),
                if: r(function(t, e, n, r) {
                    return new m.default(e, n, r)
                }),
                is_supported: r(function(t, e) {
                    return function() {
                        return e.isSupported()
                    }
                })
            }
    }, function(t, e, n) {
        "use strict";
        var r = n(43),
            i = function() {
                function t(t) {
                    this.options = t || {}, this.livesLeft = this.options.lives || 1 / 0
                }
                return t.prototype.getAssistant = function(t) {
                    return r.default.createAssistantToTheTransportManager(this, t, {
                        minPingDelay: this.options.minPingDelay,
                        maxPingDelay: this.options.maxPingDelay
                    })
                }, t.prototype.isAlive = function() {
                    return this.livesLeft > 0
                }, t.prototype.reportDeath = function() {
                    this.livesLeft -= 1
                }, t
            }();
        e.__esModule = !0, e.default = i
    }, function(t, e, n) {
        "use strict";
        var r = n(44),
            i = n(45),
            o = n(48),
            s = n(49),
            a = n(50),
            u = n(51),
            c = n(54),
            h = n(52),
            f = n(62),
            l = n(63),
            p = {
                createChannels: function() {
                    return new l.default
                },
                createConnectionManager: function(t, e) {
                    return new f.default(t, e)
                },
                createChannel: function(t, e) {
                    return new h.default(t, e)
                },
                createPrivateChannel: function(t, e) {
                    return new u.default(t, e)
                },
                createPresenceChannel: function(t, e) {
                    return new a.default(t, e)
                },
                createEncryptedChannel: function(t, e) {
                    return new c.default(t, e)
                },
                createTimelineSender: function(t, e) {
                    return new s.default(t, e)
                },
                createAuthorizer: function(t, e) {
                    return e.authorizer ? e.authorizer(t, e) : new o.default(t, e)
                },
                createHandshake: function(t, e) {
                    return new i.default(t, e)
                },
                createAssistantToTheTransportManager: function(t, e, n) {
                    return new r.default(t, e, n)
                }
            };
        e.__esModule = !0, e.default = p
    }, function(t, e, n) {
        "use strict";
        var r = n(11),
            i = n(9),
            o = function() {
                function t(t, e, n) {
                    this.manager = t, this.transport = e, this.minPingDelay = n.minPingDelay, this.maxPingDelay = n.maxPingDelay, this.pingDelay = void 0
                }
                return t.prototype.createConnection = function(t, e, n, o) {
                    var s = this;
                    o = i.extend({}, o, {
                        activityTimeout: this.pingDelay
                    });
                    var a = this.transport.createConnection(t, e, n, o),
                        u = null,
                        c = function() {
                            a.unbind("open", c), a.bind("closed", h), u = r.default.now()
                        },
                        h = function(t) {
                            if (a.unbind("closed", h), 1002 === t.code || 1003 === t.code) s.manager.reportDeath();
                            else if (!t.wasClean && u) {
                                var e = r.default.now() - u;
                                e < 2 * s.maxPingDelay && (s.manager.reportDeath(), s.pingDelay = Math.max(e / 2, s.minPingDelay))
                            }
                        };
                    return a.bind("open", c), a
                }, t.prototype.isSupported = function(t) {
                    return this.manager.isAlive() && this.transport.isSupported(t)
                }, t
            }();
        e.__esModule = !0, e.default = o
    }, function(t, e, n) {
        "use strict";
        var r = n(9),
            i = n(46),
            o = n(47),
            s = function() {
                function t(t, e) {
                    this.transport = t, this.callback = e, this.bindListeners()
                }
                return t.prototype.close = function() {
                    this.unbindListeners(), this.transport.close()
                }, t.prototype.bindListeners = function() {
                    var t = this;
                    this.onMessage = function(e) {
                        t.unbindListeners();
                        var n;
                        try {
                            n = i.processHandshake(e)
                        } catch (e) {
                            return t.finish("error", {
                                error: e
                            }), void t.transport.close()
                        }
                        "connected" === n.action ? t.finish("connected", {
                            connection: new o.default(n.id, t.transport),
                            activityTimeout: n.activityTimeout
                        }) : (t.finish(n.action, {
                            error: n.error
                        }), t.transport.close())
                    }, this.onClosed = function(e) {
                        t.unbindListeners();
                        var n = i.getCloseAction(e) || "backoff",
                            r = i.getCloseError(e);
                        t.finish(n, {
                            error: r
                        })
                    }, this.transport.bind("message", this.onMessage), this.transport.bind("closed", this.onClosed)
                }, t.prototype.unbindListeners = function() {
                    this.transport.unbind("message", this.onMessage), this.transport.unbind("closed", this.onClosed)
                }, t.prototype.finish = function(t, e) {
                    this.callback(r.extend({
                        transport: this.transport,
                        action: t
                    }, e))
                }, t
            }();
        e.__esModule = !0, e.default = s
    }, function(t, e) {
        "use strict";
        e.decodeMessage = function(t) {
            try {
                var e = JSON.parse(t.data);
                if ("string" == typeof e.data) try {
                    e.data = JSON.parse(e.data)
                } catch (t) {
                    if (!(t instanceof SyntaxError)) throw t
                }
                return e
            } catch (e) {
                throw {
                    type: "MessageParseError",
                    error: e,
                    data: t.data
                }
            }
        }, e.encodeMessage = function(t) {
            return JSON.stringify(t)
        }, e.processHandshake = function(t) {
            if (t = e.decodeMessage(t), "pusher:connection_established" === t.event) {
                if (!t.data.activity_timeout) throw "No activity timeout specified in handshake";
                return {
                    action: "connected",
                    id: t.data.socket_id,
                    activityTimeout: 1e3 * t.data.activity_timeout
                }
            }
            if ("pusher:error" === t.event) return {
                action: this.getCloseAction(t.data),
                error: this.getCloseError(t.data)
            };
            throw "Invalid handshake"
        }, e.getCloseAction = function(t) {
            return t.code < 4e3 ? t.code >= 1002 && t.code <= 1004 ? "backoff" : null : 4e3 === t.code ? "tls_only" : t.code < 4100 ? "refused" : t.code < 4200 ? "backoff" : t.code < 4300 ? "retry" : "refused"
        }, e.getCloseError = function(t) {
            return 1e3 !== t.code && 1001 !== t.code ? {
                type: "PusherError",
                data: {
                    code: t.code,
                    message: t.reason || t.message
                }
            } : null
        }
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(9),
            o = n(24),
            s = n(46),
            a = n(8),
            u = function(t) {
                function e(e, n) {
                    t.call(this), this.id = e, this.transport = n, this.activityTimeout = n.activityTimeout, this.bindListeners()
                }
                return r(e, t), e.prototype.handlesActivityChecks = function() {
                    return this.transport.handlesActivityChecks()
                }, e.prototype.send = function(t) {
                    return this.transport.send(t)
                }, e.prototype.send_event = function(t, e, n) {
                    var r = {
                        event: t,
                        data: e
                    };
                    return n && (r.channel = n), a.default.debug("Event sent", r), this.send(s.encodeMessage(r))
                }, e.prototype.ping = function() {
                    this.transport.supportsPing() ? this.transport.ping() : this.send_event("pusher:ping", {})
                }, e.prototype.close = function() {
                    this.transport.close()
                }, e.prototype.bindListeners = function() {
                    var t = this,
                        e = {
                            message: function(e) {
                                var n;
                                try {
                                    n = s.decodeMessage(e)
                                } catch (n) {
                                    t.emit("error", {
                                        type: "MessageParseError",
                                        error: n,
                                        data: e.data
                                    })
                                }
                                if (void 0 !== n) {
                                    switch (a.default.debug("Event recd", n), n.event) {
                                        case "pusher:error":
                                            t.emit("error", {
                                                type: "PusherError",
                                                data: n.data
                                            });
                                            break;
                                        case "pusher:ping":
                                            t.emit("ping");
                                            break;
                                        case "pusher:pong":
                                            t.emit("pong")
                                    }
                                    t.emit("message", n)
                                }
                            },
                            activity: function() {
                                t.emit("activity")
                            },
                            error: function(e) {
                                t.emit("error", {
                                    type: "WebSocketError",
                                    error: e
                                })
                            },
                            closed: function(e) {
                                n(), e && e.code && t.handleCloseEvent(e), t.transport = null, t.emit("closed")
                            }
                        },
                        n = function() {
                            i.objectApply(e, function(e, n) {
                                t.transport.unbind(n, e)
                            })
                        };
                    i.objectApply(e, function(e, n) {
                        t.transport.bind(n, e)
                    })
                }, e.prototype.handleCloseEvent = function(t) {
                    var e = s.getCloseAction(t),
                        n = s.getCloseError(t);
                    n && this.emit("error", n), e && this.emit(e, {
                        action: e,
                        error: n
                    })
                }, e
            }(o.default);
        e.__esModule = !0, e.default = u
    }, function(t, e, n) {
        "use strict";
        var r = n(2),
            i = function() {
                function t(t, e) {
                    this.channel = t;
                    var n = e.authTransport;
                    if ("undefined" == typeof r.default.getAuthorizers()[n]) throw "'" + n + "' is not a recognized auth transport";
                    this.type = n, this.options = e, this.authOptions = (e || {}).auth || {}
                }
                return t.prototype.composeQuery = function(t) {
                    var e = "socket_id=" + encodeURIComponent(t) + "&channel_name=" + encodeURIComponent(this.channel.name);
                    for (var n in this.authOptions.params) e += "&" + encodeURIComponent(n) + "=" + encodeURIComponent(this.authOptions.params[n]);
                    return e
                }, t.prototype.authorize = function(e, n) {
                    return t.authorizers = t.authorizers || r.default.getAuthorizers(), t.authorizers[this.type].call(this, r.default, e, n)
                }, t
            }();
        e.__esModule = !0, e.default = i
    }, function(t, e, n) {
        "use strict";
        var r = n(2),
            i = function() {
                function t(t, e) {
                    this.timeline = t, this.options = e || {}
                }
                return t.prototype.send = function(t, e) {
                    this.timeline.isEmpty() || this.timeline.send(r.default.TimelineTransport.getAgent(this, t), e)
                }, t
            }();
        e.__esModule = !0, e.default = i
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(51),
            o = n(8),
            s = n(53),
            a = n(14),
            u = function(t) {
                function e(e, n) {
                    t.call(this, e, n), this.members = new s.default
                }
                return r(e, t), e.prototype.authorize = function(e, n) {
                    var r = this;
                    t.prototype.authorize.call(this, e, function(t, e) {
                        if (!t) {
                            if (void 0 === e.channel_data) {
                                var i = a.default.buildLogSuffix("authenticationEndpoint");
                                return o.default.warn("Invalid auth response for channel '" + r.name + "',expected 'channel_data' field. " + i), void n("Invalid auth response")
                            }
                            var s = JSON.parse(e.channel_data);
                            r.members.setMyID(s.user_id)
                        }
                        n(t, e)
                    })
                }, e.prototype.handleEvent = function(t, e) {
                    switch (t) {
                        case "pusher_internal:subscription_succeeded":
                            this.subscriptionPending = !1, this.subscribed = !0, this.subscriptionCancelled ? this.pusher.unsubscribe(this.name) : (this.members.onSubscription(e), this.emit("pusher:subscription_succeeded", this.members));
                            break;
                        case "pusher_internal:member_added":
                            var n = this.members.addMember(e);
                            this.emit("pusher:member_added", n);
                            break;
                        case "pusher_internal:member_removed":
                            var r = this.members.removeMember(e);
                            r && this.emit("pusher:member_removed", r);
                            break;
                        default:
                            i.default.prototype.handleEvent.call(this, t, e)
                    }
                }, e.prototype.disconnect = function() {
                    this.members.reset(), t.prototype.disconnect.call(this)
                }, e
            }(i.default);
        e.__esModule = !0, e.default = u
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(43),
            o = n(52),
            s = function(t) {
                function e() {
                    t.apply(this, arguments)
                }
                return r(e, t), e.prototype.authorize = function(t, e) {
                    var n = i.default.createAuthorizer(this, this.pusher.config);
                    return n.authorize(t, e)
                }, e
            }(o.default);
        e.__esModule = !0, e.default = s
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(24),
            o = n(31),
            s = n(8),
            a = function(t) {
                function e(e, n) {
                    t.call(this, function(t, n) {
                        s.default.debug("No callbacks on " + e + " for " + t)
                    }), this.name = e, this.pusher = n, this.subscribed = !1, this.subscriptionPending = !1, this.subscriptionCancelled = !1
                }
                return r(e, t), e.prototype.authorize = function(t, e) {
                    return e(!1, {})
                }, e.prototype.trigger = function(t, e) {
                    if (0 !== t.indexOf("client-")) throw new o.BadEventName("Event '" + t + "' does not start with 'client-'");
                    return this.pusher.send_event(t, e, this.name)
                }, e.prototype.disconnect = function() {
                    this.subscribed = !1, this.subscriptionPending = !1
                }, e.prototype.handleEvent = function(t, e) {
                    0 === t.indexOf("pusher_internal:") ? "pusher_internal:subscription_succeeded" === t && (this.subscriptionPending = !1, this.subscribed = !0, this.subscriptionCancelled ? this.pusher.unsubscribe(this.name) : this.emit("pusher:subscription_succeeded", e)) : this.emit(t, e)
                }, e.prototype.subscribe = function() {
                    var t = this;
                    this.subscribed || (this.subscriptionPending = !0, this.subscriptionCancelled = !1, this.authorize(this.pusher.connection.socket_id, function(e, n) {
                        e ? t.handleEvent("pusher:subscription_error", n) : t.pusher.send_event("pusher:subscribe", {
                            auth: n.auth,
                            channel_data: n.channel_data,
                            channel: t.name
                        })
                    }))
                }, e.prototype.unsubscribe = function() {
                    this.subscribed = !1, this.pusher.send_event("pusher:unsubscribe", {
                        channel: this.name
                    })
                }, e.prototype.cancelSubscription = function() {
                    this.subscriptionCancelled = !0
                }, e.prototype.reinstateSubscription = function() {
                    this.subscriptionCancelled = !1
                }, e
            }(i.default);
        e.__esModule = !0, e.default = a
    }, function(t, e, n) {
        "use strict";
        var r = n(9),
            i = function() {
                function t() {
                    this.reset()
                }
                return t.prototype.get = function(t) {
                    return Object.prototype.hasOwnProperty.call(this.members, t) ? {
                        id: t,
                        info: this.members[t]
                    } : null
                }, t.prototype.each = function(t) {
                    var e = this;
                    r.objectApply(this.members, function(n, r) {
                        t(e.get(r))
                    })
                }, t.prototype.setMyID = function(t) {
                    this.myID = t
                }, t.prototype.onSubscription = function(t) {
                    this.members = t.presence.hash, this.count = t.presence.count, this.me = this.get(this.myID)
                }, t.prototype.addMember = function(t) {
                    return null === this.get(t.user_id) && this.count++, this.members[t.user_id] = t.user_info, this.get(t.user_id)
                }, t.prototype.removeMember = function(t) {
                    var e = this.get(t.user_id);
                    return e && (delete this.members[t.user_id], this.count--), e
                }, t.prototype.reset = function() {
                    this.members = {}, this.count = 0, this.myID = null, this.me = null
                }, t
            }();
        e.__esModule = !0, e.default = i
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(51),
            o = n(31),
            s = n(8),
            a = n(55),
            u = n(57),
            c = function(t) {
                function e() {
                    t.apply(this, arguments), this.key = null
                }
                return r(e, t), e.prototype.authorize = function(e, n) {
                    var r = this;
                    t.prototype.authorize.call(this, e, function(t, e) {
                        if (t) return void n(!0, e);
                        var i = e.shared_secret;
                        if (!i) {
                            var o = "No shared_secret key in auth payload for encrypted channel: " + r.name;
                            return n(!0, o), void s.default.warn("Error: " + o)
                        }
                        r.key = u.decodeBase64(i), delete e.shared_secret, n(!1, e)
                    })
                }, e.prototype.trigger = function(t, e) {
                    throw new o.UnsupportedFeature("Client events are not currently supported for encrypted channels")
                }, e.prototype.handleEvent = function(e, n) {
                    return 0 === e.indexOf("pusher_internal:") || 0 === e.indexOf("pusher:") ? void t.prototype.handleEvent.call(this, e, n) : void this.handleEncryptedEvent(e, n)
                }, e.prototype.handleEncryptedEvent = function(t, e) {
                    var n = this;
                    if (!this.key) return void s.default.debug("Received encrypted event before key has been retrieved from the authEndpoint");
                    if (!e.ciphertext || !e.nonce) return void s.default.warn("Unexpected format for encrypted event, expected object with `ciphertext` and `nonce` fields, got: " + e);
                    var r = u.decodeBase64(e.ciphertext);
                    if (r.length < a.secretbox.overheadLength) return void s.default.warn("Expected encrypted event ciphertext length to be " + a.secretbox.overheadLength + ", got: " + r.length);
                    var i = u.decodeBase64(e.nonce);
                    if (i.length < a.secretbox.nonceLength) return void s.default.warn("Expected encrypted event nonce length to be " + a.secretbox.nonceLength + ", got: " + i.length);
                    var o = a.secretbox.open(r, i, this.key);
                    return null === o ? (s.default.debug("Failed to decrypted an event, probably because it was encrypted with a different key. Fetching a new key from the authEndpoint..."), void this.authorize(this.pusher.connection.socket_id, function(e, c) {
                        return e ? void s.default.warn("Failed to make a request to the authEndpoint: " + c + ". Unable to fetch new key, so dropping encrypted event") : (o = a.secretbox.open(r, i, n.key), null === o ? void s.default.warn("Failed to decrypt event with new key. Dropping encrypted event") : void n.emitJSON(t, u.encodeUTF8(o)))
                    })) : void this.emitJSON(t, u.encodeUTF8(o))
                }, e.prototype.emitJSON = function(t, e) {
                    try {
                        this.emit(t, JSON.parse(e))
                    } catch (n) {
                        this.emit(t, e)
                    }
                    return this
                }, e
            }(i.default);
        e.__esModule = !0, e.default = c
    }, function(t, e, n) {
        ! function(t) {
            "use strict";

            function e(t, e, n, r) {
                t[e] = n >> 24 & 255, t[e + 1] = n >> 16 & 255, t[e + 2] = n >> 8 & 255, t[e + 3] = 255 & n, t[e + 4] = r >> 24 & 255, t[e + 5] = r >> 16 & 255, t[e + 6] = r >> 8 & 255, t[e + 7] = 255 & r
            }

            function r(t, e, n, r, i) {
                var o, s = 0;
                for (o = 0; o < i; o++) s |= t[e + o] ^ n[r + o];
                return (1 & s - 1 >>> 8) - 1
            }

            function i(t, e, n, i) {
                return r(t, e, n, i, 16)
            }

            function o(t, e, n, i) {
                return r(t, e, n, i, 32)
            }

            function s(t, e, n, r) {
                for (var i, o = 255 & r[0] | (255 & r[1]) << 8 | (255 & r[2]) << 16 | (255 & r[3]) << 24, s = 255 & n[0] | (255 & n[1]) << 8 | (255 & n[2]) << 16 | (255 & n[3]) << 24, a = 255 & n[4] | (255 & n[5]) << 8 | (255 & n[6]) << 16 | (255 & n[7]) << 24, u = 255 & n[8] | (255 & n[9]) << 8 | (255 & n[10]) << 16 | (255 & n[11]) << 24, c = 255 & n[12] | (255 & n[13]) << 8 | (255 & n[14]) << 16 | (255 & n[15]) << 24, h = 255 & r[4] | (255 & r[5]) << 8 | (255 & r[6]) << 16 | (255 & r[7]) << 24, f = 255 & e[0] | (255 & e[1]) << 8 | (255 & e[2]) << 16 | (255 & e[3]) << 24, l = 255 & e[4] | (255 & e[5]) << 8 | (255 & e[6]) << 16 | (255 & e[7]) << 24, p = 255 & e[8] | (255 & e[9]) << 8 | (255 & e[10]) << 16 | (255 & e[11]) << 24, d = 255 & e[12] | (255 & e[13]) << 8 | (255 & e[14]) << 16 | (255 & e[15]) << 24, y = 255 & r[8] | (255 & r[9]) << 8 | (255 & r[10]) << 16 | (255 & r[11]) << 24, g = 255 & n[16] | (255 & n[17]) << 8 | (255 & n[18]) << 16 | (255 & n[19]) << 24, v = 255 & n[20] | (255 & n[21]) << 8 | (255 & n[22]) << 16 | (255 & n[23]) << 24, b = 255 & n[24] | (255 & n[25]) << 8 | (255 & n[26]) << 16 | (255 & n[27]) << 24, m = 255 & n[28] | (255 & n[29]) << 8 | (255 & n[30]) << 16 | (255 & n[31]) << 24, w = 255 & r[12] | (255 & r[13]) << 8 | (255 & r[14]) << 16 | (255 & r[15]) << 24, _ = o, S = s, k = a, T = u, A = c, E = h, x = f, C = l, P = p, R = d, O = y, M = g, U = v, L = b, B = m, I = w, N = 0; N < 20; N += 2) i = _ + U | 0, A ^= i << 7 | i >>> 25, i = A + _ | 0, P ^= i << 9 | i >>> 23, i = P + A | 0, U ^= i << 13 | i >>> 19, i = U + P | 0, _ ^= i << 18 | i >>> 14, i = E + S | 0, R ^= i << 7 | i >>> 25, i = R + E | 0, L ^= i << 9 | i >>> 23, i = L + R | 0, S ^= i << 13 | i >>> 19, i = S + L | 0, E ^= i << 18 | i >>> 14, i = O + x | 0, B ^= i << 7 | i >>> 25, i = B + O | 0, k ^= i << 9 | i >>> 23, i = k + B | 0, x ^= i << 13 | i >>> 19, i = x + k | 0, O ^= i << 18 | i >>> 14, i = I + M | 0, T ^= i << 7 | i >>> 25, i = T + I | 0, C ^= i << 9 | i >>> 23, i = C + T | 0, M ^= i << 13 | i >>> 19, i = M + C | 0, I ^= i << 18 | i >>> 14, i = _ + T | 0, S ^= i << 7 | i >>> 25, i = S + _ | 0, k ^= i << 9 | i >>> 23, i = k + S | 0, T ^= i << 13 | i >>> 19, i = T + k | 0, _ ^= i << 18 | i >>> 14, i = E + A | 0, x ^= i << 7 | i >>> 25, i = x + E | 0, C ^= i << 9 | i >>> 23, i = C + x | 0, A ^= i << 13 | i >>> 19, i = A + C | 0, E ^= i << 18 | i >>> 14, i = O + R | 0, M ^= i << 7 | i >>> 25, i = M + O | 0, P ^= i << 9 | i >>> 23, i = P + M | 0, R ^= i << 13 | i >>> 19, i = R + P | 0, O ^= i << 18 | i >>> 14, i = I + B | 0, U ^= i << 7 | i >>> 25, i = U + I | 0, L ^= i << 9 | i >>> 23, i = L + U | 0, B ^= i << 13 | i >>> 19, i = B + L | 0, I ^= i << 18 | i >>> 14;
                _ = _ + o | 0, S = S + s | 0, k = k + a | 0, T = T + u | 0, A = A + c | 0, E = E + h | 0, x = x + f | 0, C = C + l | 0, P = P + p | 0, R = R + d | 0, O = O + y | 0, M = M + g | 0, U = U + v | 0, L = L + b | 0, B = B + m | 0, I = I + w | 0, t[0] = _ >>> 0 & 255, t[1] = _ >>> 8 & 255, t[2] = _ >>> 16 & 255, t[3] = _ >>> 24 & 255, t[4] = S >>> 0 & 255, t[5] = S >>> 8 & 255, t[6] = S >>> 16 & 255, t[7] = S >>> 24 & 255, t[8] = k >>> 0 & 255, t[9] = k >>> 8 & 255, t[10] = k >>> 16 & 255, t[11] = k >>> 24 & 255, t[12] = T >>> 0 & 255, t[13] = T >>> 8 & 255, t[14] = T >>> 16 & 255, t[15] = T >>> 24 & 255, t[16] = A >>> 0 & 255, t[17] = A >>> 8 & 255, t[18] = A >>> 16 & 255, t[19] = A >>> 24 & 255, t[20] = E >>> 0 & 255, t[21] = E >>> 8 & 255, t[22] = E >>> 16 & 255, t[23] = E >>> 24 & 255, t[24] = x >>> 0 & 255, t[25] = x >>> 8 & 255, t[26] = x >>> 16 & 255, t[27] = x >>> 24 & 255, t[28] = C >>> 0 & 255, t[29] = C >>> 8 & 255, t[30] = C >>> 16 & 255, t[31] = C >>> 24 & 255, t[32] = P >>> 0 & 255, t[33] = P >>> 8 & 255, t[34] = P >>> 16 & 255, t[35] = P >>> 24 & 255, t[36] = R >>> 0 & 255, t[37] = R >>> 8 & 255, t[38] = R >>> 16 & 255, t[39] = R >>> 24 & 255, t[40] = O >>> 0 & 255, t[41] = O >>> 8 & 255, t[42] = O >>> 16 & 255, t[43] = O >>> 24 & 255, t[44] = M >>> 0 & 255, t[45] = M >>> 8 & 255, t[46] = M >>> 16 & 255, t[47] = M >>> 24 & 255, t[48] = U >>> 0 & 255, t[49] = U >>> 8 & 255, t[50] = U >>> 16 & 255, t[51] = U >>> 24 & 255, t[52] = L >>> 0 & 255, t[53] = L >>> 8 & 255, t[54] = L >>> 16 & 255, t[55] = L >>> 24 & 255, t[56] = B >>> 0 & 255, t[57] = B >>> 8 & 255, t[58] = B >>> 16 & 255, t[59] = B >>> 24 & 255, t[60] = I >>> 0 & 255, t[61] = I >>> 8 & 255, t[62] = I >>> 16 & 255, t[63] = I >>> 24 & 255
            }

            function a(t, e, n, r) {
                for (var i, o = 255 & r[0] | (255 & r[1]) << 8 | (255 & r[2]) << 16 | (255 & r[3]) << 24, s = 255 & n[0] | (255 & n[1]) << 8 | (255 & n[2]) << 16 | (255 & n[3]) << 24, a = 255 & n[4] | (255 & n[5]) << 8 | (255 & n[6]) << 16 | (255 & n[7]) << 24, u = 255 & n[8] | (255 & n[9]) << 8 | (255 & n[10]) << 16 | (255 & n[11]) << 24, c = 255 & n[12] | (255 & n[13]) << 8 | (255 & n[14]) << 16 | (255 & n[15]) << 24, h = 255 & r[4] | (255 & r[5]) << 8 | (255 & r[6]) << 16 | (255 & r[7]) << 24, f = 255 & e[0] | (255 & e[1]) << 8 | (255 & e[2]) << 16 | (255 & e[3]) << 24, l = 255 & e[4] | (255 & e[5]) << 8 | (255 & e[6]) << 16 | (255 & e[7]) << 24, p = 255 & e[8] | (255 & e[9]) << 8 | (255 & e[10]) << 16 | (255 & e[11]) << 24, d = 255 & e[12] | (255 & e[13]) << 8 | (255 & e[14]) << 16 | (255 & e[15]) << 24, y = 255 & r[8] | (255 & r[9]) << 8 | (255 & r[10]) << 16 | (255 & r[11]) << 24, g = 255 & n[16] | (255 & n[17]) << 8 | (255 & n[18]) << 16 | (255 & n[19]) << 24, v = 255 & n[20] | (255 & n[21]) << 8 | (255 & n[22]) << 16 | (255 & n[23]) << 24, b = 255 & n[24] | (255 & n[25]) << 8 | (255 & n[26]) << 16 | (255 & n[27]) << 24, m = 255 & n[28] | (255 & n[29]) << 8 | (255 & n[30]) << 16 | (255 & n[31]) << 24, w = 255 & r[12] | (255 & r[13]) << 8 | (255 & r[14]) << 16 | (255 & r[15]) << 24, _ = o, S = s, k = a, T = u, A = c, E = h, x = f, C = l, P = p, R = d, O = y, M = g, U = v, L = b, B = m, I = w, N = 0; N < 20; N += 2) i = _ + U | 0, A ^= i << 7 | i >>> 25, i = A + _ | 0, P ^= i << 9 | i >>> 23, i = P + A | 0, U ^= i << 13 | i >>> 19, i = U + P | 0, _ ^= i << 18 | i >>> 14, i = E + S | 0, R ^= i << 7 | i >>> 25, i = R + E | 0, L ^= i << 9 | i >>> 23, i = L + R | 0, S ^= i << 13 | i >>> 19, i = S + L | 0, E ^= i << 18 | i >>> 14, i = O + x | 0, B ^= i << 7 | i >>> 25, i = B + O | 0, k ^= i << 9 | i >>> 23, i = k + B | 0, x ^= i << 13 | i >>> 19, i = x + k | 0, O ^= i << 18 | i >>> 14, i = I + M | 0, T ^= i << 7 | i >>> 25, i = T + I | 0, C ^= i << 9 | i >>> 23, i = C + T | 0, M ^= i << 13 | i >>> 19, i = M + C | 0, I ^= i << 18 | i >>> 14, i = _ + T | 0, S ^= i << 7 | i >>> 25, i = S + _ | 0, k ^= i << 9 | i >>> 23, i = k + S | 0, T ^= i << 13 | i >>> 19, i = T + k | 0, _ ^= i << 18 | i >>> 14, i = E + A | 0, x ^= i << 7 | i >>> 25, i = x + E | 0, C ^= i << 9 | i >>> 23, i = C + x | 0, A ^= i << 13 | i >>> 19, i = A + C | 0, E ^= i << 18 | i >>> 14, i = O + R | 0, M ^= i << 7 | i >>> 25, i = M + O | 0, P ^= i << 9 | i >>> 23, i = P + M | 0, R ^= i << 13 | i >>> 19, i = R + P | 0, O ^= i << 18 | i >>> 14, i = I + B | 0, U ^= i << 7 | i >>> 25, i = U + I | 0, L ^= i << 9 | i >>> 23, i = L + U | 0, B ^= i << 13 | i >>> 19, i = B + L | 0, I ^= i << 18 | i >>> 14;
                t[0] = _ >>> 0 & 255, t[1] = _ >>> 8 & 255, t[2] = _ >>> 16 & 255, t[3] = _ >>> 24 & 255, t[4] = E >>> 0 & 255, t[5] = E >>> 8 & 255, t[6] = E >>> 16 & 255, t[7] = E >>> 24 & 255, t[8] = O >>> 0 & 255, t[9] = O >>> 8 & 255, t[10] = O >>> 16 & 255, t[11] = O >>> 24 & 255, t[12] = I >>> 0 & 255, t[13] = I >>> 8 & 255, t[14] = I >>> 16 & 255, t[15] = I >>> 24 & 255, t[16] = x >>> 0 & 255, t[17] = x >>> 8 & 255, t[18] = x >>> 16 & 255, t[19] = x >>> 24 & 255, t[20] = C >>> 0 & 255, t[21] = C >>> 8 & 255, t[22] = C >>> 16 & 255, t[23] = C >>> 24 & 255, t[24] = P >>> 0 & 255, t[25] = P >>> 8 & 255, t[26] = P >>> 16 & 255, t[27] = P >>> 24 & 255, t[28] = R >>> 0 & 255, t[29] = R >>> 8 & 255, t[30] = R >>> 16 & 255, t[31] = R >>> 24 & 255
            }

            function u(t, e, n, r) {
                s(t, e, n, r)
            }

            function c(t, e, n, r) {
                a(t, e, n, r)
            }

            function h(t, e, n, r, i, o, s) {
                var a, c, h = new Uint8Array(16),
                    f = new Uint8Array(64);
                for (c = 0; c < 16; c++) h[c] = 0;
                for (c = 0; c < 8; c++) h[c] = o[c];
                for (; i >= 64;) {
                    for (u(f, h, s, lt), c = 0; c < 64; c++) t[e + c] = n[r + c] ^ f[c];
                    for (a = 1, c = 8; c < 16; c++) a = a + (255 & h[c]) | 0, h[c] = 255 & a, a >>>= 8;
                    i -= 64, e += 64, r += 64
                }
                if (i > 0)
                    for (u(f, h, s, lt), c = 0; c < i; c++) t[e + c] = n[r + c] ^ f[c];
                return 0
            }

            function f(t, e, n, r, i) {
                var o, s, a = new Uint8Array(16),
                    c = new Uint8Array(64);
                for (s = 0; s < 16; s++) a[s] = 0;
                for (s = 0; s < 8; s++) a[s] = r[s];
                for (; n >= 64;) {
                    for (u(c, a, i, lt), s = 0; s < 64; s++) t[e + s] = c[s];
                    for (o = 1, s = 8; s < 16; s++) o = o + (255 & a[s]) | 0, a[s] = 255 & o, o >>>= 8;
                    n -= 64, e += 64
                }
                if (n > 0)
                    for (u(c, a, i, lt), s = 0; s < n; s++) t[e + s] = c[s];
                return 0
            }

            function l(t, e, n, r, i) {
                var o = new Uint8Array(32);
                c(o, r, i, lt);
                for (var s = new Uint8Array(8), a = 0; a < 8; a++) s[a] = r[a + 16];
                return f(t, e, n, s, o)
            }

            function p(t, e, n, r, i, o, s) {
                var a = new Uint8Array(32);
                c(a, o, s, lt);
                for (var u = new Uint8Array(8), f = 0; f < 8; f++) u[f] = o[f + 16];
                return h(t, e, n, r, i, u, a)
            }

            function d(t, e, n, r, i, o) {
                var s = new pt(o);
                return s.update(n, r, i), s.finish(t, e), 0
            }

            function y(t, e, n, r, o, s) {
                var a = new Uint8Array(16);
                return d(a, 0, n, r, o, s), i(t, e, a, 0)
            }

            function g(t, e, n, r, i) {
                var o;
                if (n < 32) return -1;
                for (p(t, 0, e, 0, n, r, i), d(t, 16, t, 32, n - 32, t), o = 0; o < 16; o++) t[o] = 0;
                return 0
            }

            function v(t, e, n, r, i) {
                var o, s = new Uint8Array(32);
                if (n < 32) return -1;
                if (l(s, 0, 32, r, i), 0 !== y(e, 16, e, 32, n - 32, s)) return -1;
                for (p(t, 0, e, 0, n, r, i), o = 0; o < 32; o++) t[o] = 0;
                return 0
            }

            function b(t, e) {
                var n;
                for (n = 0; n < 16; n++) t[n] = 0 | e[n]
            }

            function m(t) {
                var e, n, r = 1;
                for (e = 0; e < 16; e++) n = t[e] + r + 65535, r = Math.floor(n / 65536), t[e] = n - 65536 * r;
                t[0] += r - 1 + 37 * (r - 1)
            }

            function w(t, e, n) {
                for (var r, i = ~(n - 1), o = 0; o < 16; o++) r = i & (t[o] ^ e[o]), t[o] ^= r, e[o] ^= r
            }

            function _(t, e) {
                var n, r, i, o = tt(),
                    s = tt();
                for (n = 0; n < 16; n++) s[n] = e[n];
                for (m(s), m(s), m(s), r = 0; r < 2; r++) {
                    for (o[0] = s[0] - 65517, n = 1; n < 15; n++) o[n] = s[n] - 65535 - (o[n - 1] >> 16 & 1), o[n - 1] &= 65535;
                    o[15] = s[15] - 32767 - (o[14] >> 16 & 1), i = o[15] >> 16 & 1, o[14] &= 65535, w(s, o, 1 - i)
                }
                for (n = 0; n < 16; n++) t[2 * n] = 255 & s[n], t[2 * n + 1] = s[n] >> 8
            }

            function S(t, e) {
                var n = new Uint8Array(32),
                    r = new Uint8Array(32);
                return _(n, t), _(r, e), o(n, 0, r, 0)
            }

            function k(t) {
                var e = new Uint8Array(32);
                return _(e, t), 1 & e[0]
            }

            function T(t, e) {
                var n;
                for (n = 0; n < 16; n++) t[n] = e[2 * n] + (e[2 * n + 1] << 8);
                t[15] &= 32767
            }

            function A(t, e, n) {
                for (var r = 0; r < 16; r++) t[r] = e[r] + n[r]
            }

            function E(t, e, n) {
                for (var r = 0; r < 16; r++) t[r] = e[r] - n[r]
            }

            function x(t, e, n) {
                var r, i, o = 0,
                    s = 0,
                    a = 0,
                    u = 0,
                    c = 0,
                    h = 0,
                    f = 0,
                    l = 0,
                    p = 0,
                    d = 0,
                    y = 0,
                    g = 0,
                    v = 0,
                    b = 0,
                    m = 0,
                    w = 0,
                    _ = 0,
                    S = 0,
                    k = 0,
                    T = 0,
                    A = 0,
                    E = 0,
                    x = 0,
                    C = 0,
                    P = 0,
                    R = 0,
                    O = 0,
                    M = 0,
                    U = 0,
                    L = 0,
                    B = 0,
                    I = n[0],
                    N = n[1],
                    D = n[2],
                    j = n[3],
                    Y = n[4],
                    z = n[5],
                    H = n[6],
                    q = n[7],
                    F = n[8],
                    J = n[9],
                    X = n[10],
                    K = n[11],
                    G = n[12],
                    W = n[13],
                    V = n[14],
                    Z = n[15];
                r = e[0], o += r * I, s += r * N, a += r * D, u += r * j, c += r * Y, h += r * z, f += r * H, l += r * q, p += r * F, d += r * J, y += r * X, g += r * K, v += r * G, b += r * W, m += r * V, w += r * Z, r = e[1], s += r * I, a += r * N, u += r * D, c += r * j, h += r * Y, f += r * z, l += r * H, p += r * q, d += r * F, y += r * J, g += r * X, v += r * K, b += r * G, m += r * W, w += r * V, _ += r * Z, r = e[2], a += r * I, u += r * N, c += r * D, h += r * j, f += r * Y, l += r * z, p += r * H, d += r * q, y += r * F, g += r * J, v += r * X, b += r * K, m += r * G, w += r * W, _ += r * V, S += r * Z, r = e[3], u += r * I, c += r * N, h += r * D, f += r * j, l += r * Y, p += r * z, d += r * H, y += r * q, g += r * F, v += r * J, b += r * X, m += r * K, w += r * G, _ += r * W, S += r * V, k += r * Z, r = e[4], c += r * I, h += r * N, f += r * D, l += r * j, p += r * Y, d += r * z, y += r * H, g += r * q, v += r * F, b += r * J, m += r * X, w += r * K, _ += r * G, S += r * W, k += r * V, T += r * Z, r = e[5], h += r * I, f += r * N, l += r * D, p += r * j, d += r * Y, y += r * z, g += r * H, v += r * q, b += r * F, m += r * J, w += r * X, _ += r * K, S += r * G, k += r * W, T += r * V, A += r * Z, r = e[6], f += r * I, l += r * N, p += r * D, d += r * j, y += r * Y, g += r * z, v += r * H, b += r * q, m += r * F, w += r * J, _ += r * X, S += r * K, k += r * G, T += r * W, A += r * V, E += r * Z, r = e[7], l += r * I, p += r * N, d += r * D, y += r * j, g += r * Y, v += r * z, b += r * H, m += r * q, w += r * F, _ += r * J, S += r * X, k += r * K, T += r * G, A += r * W, E += r * V, x += r * Z, r = e[8], p += r * I, d += r * N, y += r * D, g += r * j, v += r * Y, b += r * z, m += r * H, w += r * q, _ += r * F, S += r * J, k += r * X, T += r * K, A += r * G, E += r * W, x += r * V, C += r * Z, r = e[9], d += r * I, y += r * N, g += r * D, v += r * j, b += r * Y, m += r * z, w += r * H, _ += r * q, S += r * F, k += r * J, T += r * X, A += r * K, E += r * G, x += r * W, C += r * V, P += r * Z, r = e[10], y += r * I, g += r * N, v += r * D, b += r * j, m += r * Y, w += r * z, _ += r * H, S += r * q, k += r * F, T += r * J, A += r * X, E += r * K, x += r * G, C += r * W, P += r * V, R += r * Z, r = e[11], g += r * I, v += r * N, b += r * D, m += r * j, w += r * Y, _ += r * z, S += r * H, k += r * q, T += r * F, A += r * J, E += r * X, x += r * K;
                C += r * G;
                P += r * W, R += r * V, O += r * Z, r = e[12], v += r * I, b += r * N, m += r * D, w += r * j, _ += r * Y, S += r * z, k += r * H, T += r * q, A += r * F, E += r * J, x += r * X, C += r * K, P += r * G, R += r * W, O += r * V, M += r * Z, r = e[13], b += r * I, m += r * N, w += r * D, _ += r * j, S += r * Y, k += r * z, T += r * H, A += r * q, E += r * F, x += r * J, C += r * X, P += r * K, R += r * G, O += r * W, M += r * V, U += r * Z, r = e[14], m += r * I, w += r * N, _ += r * D, S += r * j, k += r * Y, T += r * z, A += r * H, E += r * q, x += r * F, C += r * J, P += r * X, R += r * K, O += r * G, M += r * W, U += r * V, L += r * Z, r = e[15], w += r * I, _ += r * N, S += r * D, k += r * j, T += r * Y, A += r * z, E += r * H, x += r * q, C += r * F, P += r * J, R += r * X, O += r * K, M += r * G, U += r * W, L += r * V, B += r * Z, o += 38 * _, s += 38 * S, a += 38 * k, u += 38 * T, c += 38 * A, h += 38 * E, f += 38 * x, l += 38 * C, p += 38 * P, d += 38 * R, y += 38 * O, g += 38 * M, v += 38 * U, b += 38 * L, m += 38 * B, i = 1, r = o + i + 65535, i = Math.floor(r / 65536), o = r - 65536 * i, r = s + i + 65535, i = Math.floor(r / 65536), s = r - 65536 * i, r = a + i + 65535, i = Math.floor(r / 65536), a = r - 65536 * i, r = u + i + 65535, i = Math.floor(r / 65536), u = r - 65536 * i, r = c + i + 65535, i = Math.floor(r / 65536), c = r - 65536 * i, r = h + i + 65535, i = Math.floor(r / 65536), h = r - 65536 * i, r = f + i + 65535, i = Math.floor(r / 65536), f = r - 65536 * i,

                    r = l + i + 65535, i = Math.floor(r / 65536), l = r - 65536 * i, r = p + i + 65535, i = Math.floor(r / 65536), p = r - 65536 * i, r = d + i + 65535, i = Math.floor(r / 65536), d = r - 65536 * i, r = y + i + 65535, i = Math.floor(r / 65536), y = r - 65536 * i, r = g + i + 65535, i = Math.floor(r / 65536), g = r - 65536 * i, r = v + i + 65535, i = Math.floor(r / 65536), v = r - 65536 * i, r = b + i + 65535, i = Math.floor(r / 65536), b = r - 65536 * i, r = m + i + 65535, i = Math.floor(r / 65536), m = r - 65536 * i, r = w + i + 65535, i = Math.floor(r / 65536), w = r - 65536 * i, o += i - 1 + 37 * (i - 1), i = 1, r = o + i + 65535, i = Math.floor(r / 65536), o = r - 65536 * i, r = s + i + 65535, i = Math.floor(r / 65536), s = r - 65536 * i, r = a + i + 65535, i = Math.floor(r / 65536), a = r - 65536 * i, r = u + i + 65535, i = Math.floor(r / 65536), u = r - 65536 * i, r = c + i + 65535, i = Math.floor(r / 65536), c = r - 65536 * i, r = h + i + 65535, i = Math.floor(r / 65536), h = r - 65536 * i, r = f + i + 65535, i = Math.floor(r / 65536), f = r - 65536 * i, r = l + i + 65535, i = Math.floor(r / 65536), l = r - 65536 * i, r = p + i + 65535, i = Math.floor(r / 65536), p = r - 65536 * i, r = d + i + 65535, i = Math.floor(r / 65536), d = r - 65536 * i, r = y + i + 65535, i = Math.floor(r / 65536), y = r - 65536 * i, r = g + i + 65535, i = Math.floor(r / 65536), g = r - 65536 * i, r = v + i + 65535, i = Math.floor(r / 65536), v = r - 65536 * i, r = b + i + 65535, i = Math.floor(r / 65536), b = r - 65536 * i, r = m + i + 65535, i = Math.floor(r / 65536), m = r - 65536 * i, r = w + i + 65535, i = Math.floor(r / 65536), w = r - 65536 * i, o += i - 1 + 37 * (i - 1), t[0] = o, t[1] = s, t[2] = a, t[3] = u, t[4] = c, t[5] = h, t[6] = f, t[7] = l, t[8] = p, t[9] = d, t[10] = y, t[11] = g, t[12] = v, t[13] = b;
                t[14] = m;
                t[15] = w
            }

            function C(t, e) {
                x(t, e, e)
            }

            function P(t, e) {
                var n, r = tt();
                for (n = 0; n < 16; n++) r[n] = e[n];
                for (n = 253; n >= 0; n--) C(r, r), 2 !== n && 4 !== n && x(r, r, e);
                for (n = 0; n < 16; n++) t[n] = r[n]
            }

            function R(t, e) {
                var n, r = tt();
                for (n = 0; n < 16; n++) r[n] = e[n];
                for (n = 250; n >= 0; n--) C(r, r), 1 !== n && x(r, r, e);
                for (n = 0; n < 16; n++) t[n] = r[n]
            }

            function O(t, e, n) {
                var r, i, o = new Uint8Array(32),
                    s = new Float64Array(80),
                    a = tt(),
                    u = tt(),
                    c = tt(),
                    h = tt(),
                    f = tt(),
                    l = tt();
                for (i = 0; i < 31; i++) o[i] = e[i];
                for (o[31] = 127 & e[31] | 64, o[0] &= 248, T(s, n), i = 0; i < 16; i++) u[i] = s[i], h[i] = a[i] = c[i] = 0;
                for (a[0] = h[0] = 1, i = 254; i >= 0; --i) r = o[i >>> 3] >>> (7 & i) & 1, w(a, u, r), w(c, h, r), A(f, a, c), E(a, a, c), A(c, u, h), E(u, u, h), C(h, f), C(l, a), x(a, c, a), x(c, u, f), A(f, a, c), E(a, a, c), C(u, a), E(c, h, l), x(a, c, st), A(a, a, h), x(c, c, a), x(a, h, l), x(h, u, s), C(u, f), w(a, u, r), w(c, h, r);
                for (i = 0; i < 16; i++) s[i + 16] = a[i], s[i + 32] = c[i], s[i + 48] = u[i], s[i + 64] = h[i];
                var p = s.subarray(32),
                    d = s.subarray(16);
                return P(p, p), x(d, d, p), _(t, d), 0
            }

            function M(t, e) {
                return O(t, e, rt)
            }

            function U(t, e) {
                return et(e, 32), M(t, e)
            }

            function L(t, e, n) {
                var r = new Uint8Array(32);
                return O(r, n, e), c(t, nt, r, lt)
            }

            function B(t, e, n, r, i, o) {
                var s = new Uint8Array(32);
                return L(s, i, o), dt(t, e, n, r, s)
            }

            function I(t, e, n, r, i, o) {
                var s = new Uint8Array(32);
                return L(s, i, o), yt(t, e, n, r, s)
            }

            function N(t, e, n, r) {
                for (var i, o, s, a, u, c, h, f, l, p, d, y, g, v, b, m, w, _, S, k, T, A, E, x, C, P, R = new Int32Array(16), O = new Int32Array(16), M = t[0], U = t[1], L = t[2], B = t[3], I = t[4], N = t[5], D = t[6], j = t[7], Y = e[0], z = e[1], H = e[2], q = e[3], F = e[4], J = e[5], X = e[6], K = e[7], G = 0; r >= 128;) {
                    for (S = 0; S < 16; S++) k = 8 * S + G, R[S] = n[k + 0] << 24 | n[k + 1] << 16 | n[k + 2] << 8 | n[k + 3], O[S] = n[k + 4] << 24 | n[k + 5] << 16 | n[k + 6] << 8 | n[k + 7];
                    for (S = 0; S < 80; S++)
                        if (i = M, o = U, s = L, a = B, u = I, c = N, h = D, f = j, l = Y, p = z, d = H, y = q, g = F, v = J, b = X, m = K, T = j, A = K, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = (I >>> 14 | F << 18) ^ (I >>> 18 | F << 14) ^ (F >>> 9 | I << 23), A = (F >>> 14 | I << 18) ^ (F >>> 18 | I << 14) ^ (I >>> 9 | F << 23), E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, T = I & N ^ ~I & D, A = F & J ^ ~F & X, E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, T = gt[2 * S], A = gt[2 * S + 1], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, T = R[S % 16], A = O[S % 16], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, w = 65535 & C | P << 16, _ = 65535 & E | x << 16, T = w, A = _, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = (M >>> 28 | Y << 4) ^ (Y >>> 2 | M << 30) ^ (Y >>> 7 | M << 25), A = (Y >>> 28 | M << 4) ^ (M >>> 2 | Y << 30) ^ (M >>> 7 | Y << 25), E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, T = M & U ^ M & L ^ U & L, A = Y & z ^ Y & H ^ z & H, E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, f = 65535 & C | P << 16, m = 65535 & E | x << 16, T = a, A = y, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = w, A = _, E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, a = 65535 & C | P << 16, y = 65535 & E | x << 16, U = i, L = o, B = s, I = a, N = u, D = c, j = h, M = f, z = l, H = p, q = d, F = y, J = g, X = v, K = b, Y = m, S % 16 === 15)
                            for (k = 0; k < 16; k++) T = R[k], A = O[k], E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = R[(k + 9) % 16], A = O[(k + 9) % 16], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, w = R[(k + 1) % 16], _ = O[(k + 1) % 16], T = (w >>> 1 | _ << 31) ^ (w >>> 8 | _ << 24) ^ w >>> 7, A = (_ >>> 1 | w << 31) ^ (_ >>> 8 | w << 24) ^ (_ >>> 7 | w << 25), E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, w = R[(k + 14) % 16], _ = O[(k + 14) % 16], T = (w >>> 19 | _ << 13) ^ (_ >>> 29 | w << 3) ^ w >>> 6, A = (_ >>> 19 | w << 13) ^ (w >>> 29 | _ << 3) ^ (_ >>> 6 | w << 26), E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, R[k] = 65535 & C | P << 16, O[k] = 65535 & E | x << 16;
                    T = M, A = Y, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = t[0], A = e[0], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, t[0] = M = 65535 & C | P << 16, e[0] = Y = 65535 & E | x << 16, T = U, A = z, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = t[1], A = e[1], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, t[1] = U = 65535 & C | P << 16, e[1] = z = 65535 & E | x << 16, T = L, A = H, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = t[2], A = e[2], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, t[2] = L = 65535 & C | P << 16, e[2] = H = 65535 & E | x << 16, T = B, A = q, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = t[3], A = e[3], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, t[3] = B = 65535 & C | P << 16, e[3] = q = 65535 & E | x << 16, T = I, A = F, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = t[4], A = e[4], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, t[4] = I = 65535 & C | P << 16, e[4] = F = 65535 & E | x << 16, T = N, A = J, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = t[5], A = e[5], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, t[5] = N = 65535 & C | P << 16, e[5] = J = 65535 & E | x << 16, T = D, A = X, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = t[6], A = e[6], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, t[6] = D = 65535 & C | P << 16, e[6] = X = 65535 & E | x << 16, T = j, A = K, E = 65535 & A, x = A >>> 16, C = 65535 & T, P = T >>> 16, T = t[7], A = e[7], E += 65535 & A, x += A >>> 16, C += 65535 & T, P += T >>> 16, x += E >>> 16, C += x >>> 16, P += C >>> 16, t[7] = j = 65535 & C | P << 16, e[7] = K = 65535 & E | x << 16, G += 128, r -= 128
                }
                return r
            }

            function D(t, n, r) {
                var i, o = new Int32Array(8),
                    s = new Int32Array(8),
                    a = new Uint8Array(256),
                    u = r;
                for (o[0] = 1779033703, o[1] = 3144134277, o[2] = 1013904242, o[3] = 2773480762, o[4] = 1359893119, o[5] = 2600822924, o[6] = 528734635, o[7] = 1541459225, s[0] = 4089235720, s[1] = 2227873595, s[2] = 4271175723, s[3] = 1595750129, s[4] = 2917565137, s[5] = 725511199, s[6] = 4215389547, s[7] = 327033209, N(o, s, n, r), r %= 128, i = 0; i < r; i++) a[i] = n[u - r + i];
                for (a[r] = 128, r = 256 - 128 * (r < 112 ? 1 : 0), a[r - 9] = 0, e(a, r - 8, u / 536870912 | 0, u << 3), N(o, s, a, r), i = 0; i < 8; i++) e(t, 8 * i, o[i], s[i]);
                return 0
            }

            function j(t, e) {
                var n = tt(),
                    r = tt(),
                    i = tt(),
                    o = tt(),
                    s = tt(),
                    a = tt(),
                    u = tt(),
                    c = tt(),
                    h = tt();
                E(n, t[1], t[0]), E(h, e[1], e[0]), x(n, n, h), A(r, t[0], t[1]), A(h, e[0], e[1]), x(r, r, h), x(i, t[3], e[3]), x(i, i, ut), x(o, t[2], e[2]), A(o, o, o), E(s, r, n), E(a, o, i), A(u, o, i), A(c, r, n), x(t[0], s, a), x(t[1], c, u), x(t[2], u, a), x(t[3], s, c)
            }

            function Y(t, e, n) {
                var r;
                for (r = 0; r < 4; r++) w(t[r], e[r], n)
            }

            function z(t, e) {
                var n = tt(),
                    r = tt(),
                    i = tt();
                P(i, e[2]), x(n, e[0], i), x(r, e[1], i), _(t, r), t[31] ^= k(n) << 7
            }

            function H(t, e, n) {
                var r, i;
                for (b(t[0], it), b(t[1], ot), b(t[2], ot), b(t[3], it), i = 255; i >= 0; --i) r = n[i / 8 | 0] >> (7 & i) & 1, Y(t, e, r), j(e, t), j(t, t), Y(t, e, r)
            }

            function q(t, e) {
                var n = [tt(), tt(), tt(), tt()];
                b(n[0], ct), b(n[1], ht), b(n[2], ot), x(n[3], ct, ht), H(t, n, e)
            }

            function F(t, e, n) {
                var r, i = new Uint8Array(64),
                    o = [tt(), tt(), tt(), tt()];
                for (n || et(e, 32), D(i, e, 32), i[0] &= 248, i[31] &= 127, i[31] |= 64, q(o, i), z(t, o), r = 0; r < 32; r++) e[r + 32] = t[r];
                return 0
            }

            function J(t, e) {
                var n, r, i, o;
                for (r = 63; r >= 32; --r) {
                    for (n = 0, i = r - 32, o = r - 12; i < o; ++i) e[i] += n - 16 * e[r] * vt[i - (r - 32)], n = e[i] + 128 >> 8, e[i] -= 256 * n;
                    e[i] += n, e[r] = 0
                }
                for (n = 0, i = 0; i < 32; i++) e[i] += n - (e[31] >> 4) * vt[i], n = e[i] >> 8, e[i] &= 255;
                for (i = 0; i < 32; i++) e[i] -= n * vt[i];
                for (r = 0; r < 32; r++) e[r + 1] += e[r] >> 8, t[r] = 255 & e[r]
            }

            function X(t) {
                var e, n = new Float64Array(64);
                for (e = 0; e < 64; e++) n[e] = t[e];
                for (e = 0; e < 64; e++) t[e] = 0;
                J(t, n)
            }

            function K(t, e, n, r) {
                var i, o, s = new Uint8Array(64),
                    a = new Uint8Array(64),
                    u = new Uint8Array(64),
                    c = new Float64Array(64),
                    h = [tt(), tt(), tt(), tt()];
                D(s, r, 32), s[0] &= 248, s[31] &= 127, s[31] |= 64;
                var f = n + 64;
                for (i = 0; i < n; i++) t[64 + i] = e[i];
                for (i = 0; i < 32; i++) t[32 + i] = s[32 + i];
                for (D(u, t.subarray(32), n + 32), X(u), q(h, u), z(t, h), i = 32; i < 64; i++) t[i] = r[i];
                for (D(a, t, n + 64), X(a), i = 0; i < 64; i++) c[i] = 0;
                for (i = 0; i < 32; i++) c[i] = u[i];
                for (i = 0; i < 32; i++)
                    for (o = 0; o < 32; o++) c[i + o] += a[i] * s[o];
                return J(t.subarray(32), c), f
            }

            function G(t, e) {
                var n = tt(),
                    r = tt(),
                    i = tt(),
                    o = tt(),
                    s = tt(),
                    a = tt(),
                    u = tt();
                return b(t[2], ot), T(t[1], e), C(i, t[1]), x(o, i, at), E(i, i, t[2]), A(o, t[2], o), C(s, o), C(a, s), x(u, a, s), x(n, u, i), x(n, n, o), R(n, n), x(n, n, i), x(n, n, o), x(n, n, o), x(t[0], n, o), C(r, t[0]), x(r, r, o), S(r, i) && x(t[0], t[0], ft), C(r, t[0]), x(r, r, o), S(r, i) ? -1 : (k(t[0]) === e[31] >> 7 && E(t[0], it, t[0]), x(t[3], t[0], t[1]), 0)
            }

            function W(t, e, n, r) {
                var i, s, a = new Uint8Array(32),
                    u = new Uint8Array(64),
                    c = [tt(), tt(), tt(), tt()],
                    h = [tt(), tt(), tt(), tt()];
                if (s = -1, n < 64) return -1;
                if (G(h, r)) return -1;
                for (i = 0; i < n; i++) t[i] = e[i];
                for (i = 0; i < 32; i++) t[i + 32] = r[i];
                if (D(u, t, n), X(u), H(c, h, u), q(h, e.subarray(32)), j(c, h), z(a, c), n -= 64, o(e, 0, a, 0)) {
                    for (i = 0; i < n; i++) t[i] = 0;
                    return -1
                }
                for (i = 0; i < n; i++) t[i] = e[i + 64];
                return s = n
            }

            function V(t, e) {
                if (t.length !== bt) throw new Error("bad key size");
                if (e.length !== mt) throw new Error("bad nonce size")
            }

            function Z(t, e) {
                if (t.length !== Tt) throw new Error("bad public key size");
                if (e.length !== At) throw new Error("bad secret key size")
            }

            function Q() {
                for (var t = 0; t < arguments.length; t++)
                    if (!(arguments[t] instanceof Uint8Array)) throw new TypeError("unexpected type, use Uint8Array")
            }

            function $(t) {
                for (var e = 0; e < t.length; e++) t[e] = 0
            }
            var tt = function(t) {
                    var e, n = new Float64Array(16);
                    if (t)
                        for (e = 0; e < t.length; e++) n[e] = t[e];
                    return n
                },
                et = function() {
                    throw new Error("no PRNG")
                },
                nt = new Uint8Array(16),
                rt = new Uint8Array(32);
            rt[0] = 9;
            var it = tt(),
                ot = tt([1]),
                st = tt([56129, 1]),
                at = tt([30883, 4953, 19914, 30187, 55467, 16705, 2637, 112, 59544, 30585, 16505, 36039, 65139, 11119, 27886, 20995]),
                ut = tt([61785, 9906, 39828, 60374, 45398, 33411, 5274, 224, 53552, 61171, 33010, 6542, 64743, 22239, 55772, 9222]),
                ct = tt([54554, 36645, 11616, 51542, 42930, 38181, 51040, 26924, 56412, 64982, 57905, 49316, 21502, 52590, 14035, 8553]),
                ht = tt([26200, 26214, 26214, 26214, 26214, 26214, 26214, 26214, 26214, 26214, 26214, 26214, 26214, 26214, 26214, 26214]),
                ft = tt([41136, 18958, 6951, 50414, 58488, 44335, 6150, 12099, 55207, 15867, 153, 11085, 57099, 20417, 9344, 11139]),
                lt = new Uint8Array([101, 120, 112, 97, 110, 100, 32, 51, 50, 45, 98, 121, 116, 101, 32, 107]),
                pt = function(t) {
                    this.buffer = new Uint8Array(16), this.r = new Uint16Array(10), this.h = new Uint16Array(10), this.pad = new Uint16Array(8), this.leftover = 0, this.fin = 0;
                    var e, n, r, i, o, s, a, u;
                    e = 255 & t[0] | (255 & t[1]) << 8, this.r[0] = 8191 & e, n = 255 & t[2] | (255 & t[3]) << 8, this.r[1] = 8191 & (e >>> 13 | n << 3), r = 255 & t[4] | (255 & t[5]) << 8, this.r[2] = 7939 & (n >>> 10 | r << 6), i = 255 & t[6] | (255 & t[7]) << 8, this.r[3] = 8191 & (r >>> 7 | i << 9), o = 255 & t[8] | (255 & t[9]) << 8, this.r[4] = 255 & (i >>> 4 | o << 12), this.r[5] = o >>> 1 & 8190, s = 255 & t[10] | (255 & t[11]) << 8, this.r[6] = 8191 & (o >>> 14 | s << 2), a = 255 & t[12] | (255 & t[13]) << 8, this.r[7] = 8065 & (s >>> 11 | a << 5), u = 255 & t[14] | (255 & t[15]) << 8, this.r[8] = 8191 & (a >>> 8 | u << 8), this.r[9] = u >>> 5 & 127, this.pad[0] = 255 & t[16] | (255 & t[17]) << 8, this.pad[1] = 255 & t[18] | (255 & t[19]) << 8, this.pad[2] = 255 & t[20] | (255 & t[21]) << 8, this.pad[3] = 255 & t[22] | (255 & t[23]) << 8, this.pad[4] = 255 & t[24] | (255 & t[25]) << 8, this.pad[5] = 255 & t[26] | (255 & t[27]) << 8, this.pad[6] = 255 & t[28] | (255 & t[29]) << 8, this.pad[7] = 255 & t[30] | (255 & t[31]) << 8
                };
            pt.prototype.blocks = function(t, e, n) {
                for (var r, i, o, s, a, u, c, h, f, l, p, d, y, g, v, b, m, w, _, S = this.fin ? 0 : 2048, k = this.h[0], T = this.h[1], A = this.h[2], E = this.h[3], x = this.h[4], C = this.h[5], P = this.h[6], R = this.h[7], O = this.h[8], M = this.h[9], U = this.r[0], L = this.r[1], B = this.r[2], I = this.r[3], N = this.r[4], D = this.r[5], j = this.r[6], Y = this.r[7], z = this.r[8], H = this.r[9]; n >= 16;) r = 255 & t[e + 0] | (255 & t[e + 1]) << 8, k += 8191 & r, i = 255 & t[e + 2] | (255 & t[e + 3]) << 8, T += 8191 & (r >>> 13 | i << 3), o = 255 & t[e + 4] | (255 & t[e + 5]) << 8, A += 8191 & (i >>> 10 | o << 6), s = 255 & t[e + 6] | (255 & t[e + 7]) << 8, E += 8191 & (o >>> 7 | s << 9), a = 255 & t[e + 8] | (255 & t[e + 9]) << 8, x += 8191 & (s >>> 4 | a << 12), C += a >>> 1 & 8191, u = 255 & t[e + 10] | (255 & t[e + 11]) << 8, P += 8191 & (a >>> 14 | u << 2), c = 255 & t[e + 12] | (255 & t[e + 13]) << 8, R += 8191 & (u >>> 11 | c << 5), h = 255 & t[e + 14] | (255 & t[e + 15]) << 8, O += 8191 & (c >>> 8 | h << 8), M += h >>> 5 | S, f = 0, l = f, l += k * U, l += T * (5 * H), l += A * (5 * z), l += E * (5 * Y), l += x * (5 * j), f = l >>> 13, l &= 8191, l += C * (5 * D), l += P * (5 * N), l += R * (5 * I), l += O * (5 * B), l += M * (5 * L), f += l >>> 13, l &= 8191, p = f, p += k * L, p += T * U, p += A * (5 * H), p += E * (5 * z), p += x * (5 * Y), f = p >>> 13, p &= 8191, p += C * (5 * j), p += P * (5 * D), p += R * (5 * N), p += O * (5 * I), p += M * (5 * B), f += p >>> 13, p &= 8191, d = f, d += k * B, d += T * L, d += A * U, d += E * (5 * H), d += x * (5 * z), f = d >>> 13, d &= 8191, d += C * (5 * Y), d += P * (5 * j), d += R * (5 * D), d += O * (5 * N), d += M * (5 * I), f += d >>> 13, d &= 8191, y = f, y += k * I, y += T * B, y += A * L, y += E * U, y += x * (5 * H), f = y >>> 13, y &= 8191, y += C * (5 * z), y += P * (5 * Y), y += R * (5 * j), y += O * (5 * D), y += M * (5 * N), f += y >>> 13, y &= 8191, g = f, g += k * N, g += T * I, g += A * B, g += E * L, g += x * U, f = g >>> 13, g &= 8191, g += C * (5 * H), g += P * (5 * z), g += R * (5 * Y), g += O * (5 * j), g += M * (5 * D), f += g >>> 13, g &= 8191, v = f, v += k * D, v += T * N, v += A * I, v += E * B, v += x * L, f = v >>> 13, v &= 8191, v += C * U, v += P * (5 * H), v += R * (5 * z), v += O * (5 * Y), v += M * (5 * j), f += v >>> 13, v &= 8191, b = f, b += k * j, b += T * D, b += A * N, b += E * I, b += x * B, f = b >>> 13, b &= 8191, b += C * L, b += P * U, b += R * (5 * H), b += O * (5 * z), b += M * (5 * Y), f += b >>> 13, b &= 8191, m = f, m += k * Y, m += T * j, m += A * D, m += E * N, m += x * I, f = m >>> 13, m &= 8191, m += C * B, m += P * L, m += R * U, m += O * (5 * H), m += M * (5 * z), f += m >>> 13, m &= 8191, w = f, w += k * z, w += T * Y, w += A * j, w += E * D, w += x * N, f = w >>> 13, w &= 8191, w += C * I, w += P * B, w += R * L, w += O * U, w += M * (5 * H), f += w >>> 13, w &= 8191, _ = f, _ += k * H, _ += T * z, _ += A * Y, _ += E * j, _ += x * D, f = _ >>> 13, _ &= 8191, _ += C * N, _ += P * I, _ += R * B, _ += O * L, _ += M * U, f += _ >>> 13, _ &= 8191, f = (f << 2) + f | 0, f = f + l | 0, l = 8191 & f, f >>>= 13, p += f, k = l, T = p, A = d, E = y, x = g, C = v, P = b, R = m, O = w, M = _, e += 16, n -= 16;
                this.h[0] = k, this.h[1] = T, this.h[2] = A, this.h[3] = E, this.h[4] = x, this.h[5] = C, this.h[6] = P, this.h[7] = R, this.h[8] = O, this.h[9] = M
            }, pt.prototype.finish = function(t, e) {
                var n, r, i, o, s = new Uint16Array(10);
                if (this.leftover) {
                    for (o = this.leftover, this.buffer[o++] = 1; o < 16; o++) this.buffer[o] = 0;
                    this.fin = 1, this.blocks(this.buffer, 0, 16)
                }
                for (n = this.h[1] >>> 13, this.h[1] &= 8191, o = 2; o < 10; o++) this.h[o] += n, n = this.h[o] >>> 13, this.h[o] &= 8191;
                for (this.h[0] += 5 * n, n = this.h[0] >>> 13, this.h[0] &= 8191, this.h[1] += n, n = this.h[1] >>> 13, this.h[1] &= 8191, this.h[2] += n, s[0] = this.h[0] + 5, n = s[0] >>> 13, s[0] &= 8191, o = 1; o < 10; o++) s[o] = this.h[o] + n, n = s[o] >>> 13, s[o] &= 8191;
                for (s[9] -= 8192, r = (1 ^ n) - 1, o = 0; o < 10; o++) s[o] &= r;
                for (r = ~r, o = 0; o < 10; o++) this.h[o] = this.h[o] & r | s[o];
                for (this.h[0] = 65535 & (this.h[0] | this.h[1] << 13), this.h[1] = 65535 & (this.h[1] >>> 3 | this.h[2] << 10), this.h[2] = 65535 & (this.h[2] >>> 6 | this.h[3] << 7), this.h[3] = 65535 & (this.h[3] >>> 9 | this.h[4] << 4), this.h[4] = 65535 & (this.h[4] >>> 12 | this.h[5] << 1 | this.h[6] << 14), this.h[5] = 65535 & (this.h[6] >>> 2 | this.h[7] << 11), this.h[6] = 65535 & (this.h[7] >>> 5 | this.h[8] << 8), this.h[7] = 65535 & (this.h[8] >>> 8 | this.h[9] << 5), i = this.h[0] + this.pad[0], this.h[0] = 65535 & i, o = 1; o < 8; o++) i = (this.h[o] + this.pad[o] | 0) + (i >>> 16) | 0, this.h[o] = 65535 & i;
                t[e + 0] = this.h[0] >>> 0 & 255, t[e + 1] = this.h[0] >>> 8 & 255, t[e + 2] = this.h[1] >>> 0 & 255, t[e + 3] = this.h[1] >>> 8 & 255, t[e + 4] = this.h[2] >>> 0 & 255, t[e + 5] = this.h[2] >>> 8 & 255, t[e + 6] = this.h[3] >>> 0 & 255, t[e + 7] = this.h[3] >>> 8 & 255, t[e + 8] = this.h[4] >>> 0 & 255, t[e + 9] = this.h[4] >>> 8 & 255, t[e + 10] = this.h[5] >>> 0 & 255, t[e + 11] = this.h[5] >>> 8 & 255, t[e + 12] = this.h[6] >>> 0 & 255, t[e + 13] = this.h[6] >>> 8 & 255, t[e + 14] = this.h[7] >>> 0 & 255, t[e + 15] = this.h[7] >>> 8 & 255
            }, pt.prototype.update = function(t, e, n) {
                var r, i;
                if (this.leftover) {
                    for (i = 16 - this.leftover, i > n && (i = n), r = 0; r < i; r++) this.buffer[this.leftover + r] = t[e + r];
                    if (n -= i, e += i, this.leftover += i, this.leftover < 16) return;
                    this.blocks(this.buffer, 0, 16), this.leftover = 0
                }
                if (n >= 16 && (i = n - n % 16, this.blocks(t, e, i), e += i, n -= i), n) {
                    for (r = 0; r < n; r++) this.buffer[this.leftover + r] = t[e + r];
                    this.leftover += n
                }
            };
            var dt = g,
                yt = v,
                gt = [1116352408, 3609767458, 1899447441, 602891725, 3049323471, 3964484399, 3921009573, 2173295548, 961987163, 4081628472, 1508970993, 3053834265, 2453635748, 2937671579, 2870763221, 3664609560, 3624381080, 2734883394, 310598401, 1164996542, 607225278, 1323610764, 1426881987, 3590304994, 1925078388, 4068182383, 2162078206, 991336113, 2614888103, 633803317, 3248222580, 3479774868, 3835390401, 2666613458, 4022224774, 944711139, 264347078, 2341262773, 604807628, 2007800933, 770255983, 1495990901, 1249150122, 1856431235, 1555081692, 3175218132, 1996064986, 2198950837, 2554220882, 3999719339, 2821834349, 766784016, 2952996808, 2566594879, 3210313671, 3203337956, 3336571891, 1034457026, 3584528711, 2466948901, 113926993, 3758326383, 338241895, 168717936, 666307205, 1188179964, 773529912, 1546045734, 1294757372, 1522805485, 1396182291, 2643833823, 1695183700, 2343527390, 1986661051, 1014477480, 2177026350, 1206759142, 2456956037, 344077627, 2730485921, 1290863460, 2820302411, 3158454273, 3259730800, 3505952657, 3345764771, 106217008, 3516065817, 3606008344, 3600352804, 1432725776, 4094571909, 1467031594, 275423344, 851169720, 430227734, 3100823752, 506948616, 1363258195, 659060556, 3750685593, 883997877, 3785050280, 958139571, 3318307427, 1322822218, 3812723403, 1537002063, 2003034995, 1747873779, 3602036899, 1955562222, 1575990012, 2024104815, 1125592928, 2227730452, 2716904306, 2361852424, 442776044, 2428436474, 593698344, 2756734187, 3733110249, 3204031479, 2999351573, 3329325298, 3815920427, 3391569614, 3928383900, 3515267271, 566280711, 3940187606, 3454069534, 4118630271, 4000239992, 116418474, 1914138554, 174292421, 2731055270, 289380356, 3203993006, 460393269, 320620315, 685471733, 587496836, 852142971, 1086792851, 1017036298, 365543100, 1126000580, 2618297676, 1288033470, 3409855158, 1501505948, 4234509866, 1607167915, 987167468, 1816402316, 1246189591],
                vt = new Float64Array([237, 211, 245, 92, 26, 99, 18, 88, 214, 156, 247, 162, 222, 249, 222, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 16]),
                bt = 32,
                mt = 24,
                wt = 32,
                _t = 16,
                St = 32,
                kt = 32,
                Tt = 32,
                At = 32,
                Et = 32,
                xt = mt,
                Ct = wt,
                Pt = _t,
                Rt = 64,
                Ot = 32,
                Mt = 64,
                Ut = 32,
                Lt = 64;
            t.lowlevel = {
                    crypto_core_hsalsa20: c,
                    crypto_stream_xor: p,
                    crypto_stream: l,
                    crypto_stream_salsa20_xor: h,
                    crypto_stream_salsa20: f,
                    crypto_onetimeauth: d,
                    crypto_onetimeauth_verify: y,
                    crypto_verify_16: i,
                    crypto_verify_32: o,
                    crypto_secretbox: g,
                    crypto_secretbox_open: v,
                    crypto_scalarmult: O,
                    crypto_scalarmult_base: M,
                    crypto_box_beforenm: L,
                    crypto_box_afternm: dt,
                    crypto_box: B,
                    crypto_box_open: I,
                    crypto_box_keypair: U,
                    crypto_hash: D,
                    crypto_sign: K,
                    crypto_sign_keypair: F,
                    crypto_sign_open: W,
                    crypto_secretbox_KEYBYTES: bt,
                    crypto_secretbox_NONCEBYTES: mt,
                    crypto_secretbox_ZEROBYTES: wt,
                    crypto_secretbox_BOXZEROBYTES: _t,
                    crypto_scalarmult_BYTES: St,
                    crypto_scalarmult_SCALARBYTES: kt,
                    crypto_box_PUBLICKEYBYTES: Tt,
                    crypto_box_SECRETKEYBYTES: At,
                    crypto_box_BEFORENMBYTES: Et,
                    crypto_box_NONCEBYTES: xt,
                    crypto_box_ZEROBYTES: Ct,
                    crypto_box_BOXZEROBYTES: Pt,
                    crypto_sign_BYTES: Rt,
                    crypto_sign_PUBLICKEYBYTES: Ot,
                    crypto_sign_SECRETKEYBYTES: Mt,
                    crypto_sign_SEEDBYTES: Ut,
                    crypto_hash_BYTES: Lt
                }, t.randomBytes = function(t) {
                    var e = new Uint8Array(t);
                    return et(e, t), e
                }, t.secretbox = function(t, e, n) {
                    Q(t, e, n), V(n, e);
                    for (var r = new Uint8Array(wt + t.length), i = new Uint8Array(r.length), o = 0; o < t.length; o++) r[o + wt] = t[o];
                    return g(i, r, r.length, e, n), i.subarray(_t)
                }, t.secretbox.open = function(t, e, n) {
                    Q(t, e, n), V(n, e);
                    for (var r = new Uint8Array(_t + t.length), i = new Uint8Array(r.length), o = 0; o < t.length; o++) r[o + _t] = t[o];
                    return r.length < 32 ? null : 0 !== v(i, r, r.length, e, n) ? null : i.subarray(wt)
                }, t.secretbox.keyLength = bt, t.secretbox.nonceLength = mt, t.secretbox.overheadLength = _t, t.scalarMult = function(t, e) {
                    if (Q(t, e), t.length !== kt) throw new Error("bad n size");
                    if (e.length !== St) throw new Error("bad p size");
                    var n = new Uint8Array(St);
                    return O(n, t, e), n
                }, t.scalarMult.base = function(t) {
                    if (Q(t), t.length !== kt) throw new Error("bad n size");
                    var e = new Uint8Array(St);
                    return M(e, t), e
                }, t.scalarMult.scalarLength = kt, t.scalarMult.groupElementLength = St, t.box = function(e, n, r, i) {
                    var o = t.box.before(r, i);
                    return t.secretbox(e, n, o)
                }, t.box.before = function(t, e) {
                    Q(t, e), Z(t, e);
                    var n = new Uint8Array(Et);
                    return L(n, t, e), n
                }, t.box.after = t.secretbox, t.box.open = function(e, n, r, i) {
                    var o = t.box.before(r, i);
                    return t.secretbox.open(e, n, o)
                }, t.box.open.after = t.secretbox.open, t.box.keyPair = function() {
                    var t = new Uint8Array(Tt),
                        e = new Uint8Array(At);
                    return U(t, e), {
                        publicKey: t,
                        secretKey: e
                    }
                }, t.box.keyPair.fromSecretKey = function(t) {
                    if (Q(t), t.length !== At) throw new Error("bad secret key size");
                    var e = new Uint8Array(Tt);
                    return M(e, t), {
                        publicKey: e,
                        secretKey: new Uint8Array(t)
                    }
                }, t.box.publicKeyLength = Tt, t.box.secretKeyLength = At, t.box.sharedKeyLength = Et, t.box.nonceLength = xt, t.box.overheadLength = t.secretbox.overheadLength, t.sign = function(t, e) {
                    if (Q(t, e), e.length !== Mt) throw new Error("bad secret key size");
                    var n = new Uint8Array(Rt + t.length);
                    return K(n, t, t.length, e), n
                }, t.sign.open = function(t, e) {
                    if (Q(t, e), e.length !== Ot) throw new Error("bad public key size");
                    var n = new Uint8Array(t.length),
                        r = W(n, t, t.length, e);
                    if (r < 0) return null;
                    for (var i = new Uint8Array(r), o = 0; o < i.length; o++) i[o] = n[o];
                    return i
                }, t.sign.detached = function(e, n) {
                    for (var r = t.sign(e, n), i = new Uint8Array(Rt), o = 0; o < i.length; o++) i[o] = r[o];
                    return i
                }, t.sign.detached.verify = function(t, e, n) {
                    if (Q(t, e, n), e.length !== Rt) throw new Error("bad signature size");
                    if (n.length !== Ot) throw new Error("bad public key size");
                    var r, i = new Uint8Array(Rt + t.length),
                        o = new Uint8Array(Rt + t.length);
                    for (r = 0; r < Rt; r++) i[r] = e[r];
                    for (r = 0; r < t.length; r++) i[r + Rt] = t[r];
                    return W(o, i, i.length, n) >= 0
                }, t.sign.keyPair = function() {
                    var t = new Uint8Array(Ot),
                        e = new Uint8Array(Mt);
                    return F(t, e), {
                        publicKey: t,
                        secretKey: e
                    }
                }, t.sign.keyPair.fromSecretKey = function(t) {
                    if (Q(t), t.length !== Mt) throw new Error("bad secret key size");
                    for (var e = new Uint8Array(Ot), n = 0; n < e.length; n++) e[n] = t[32 + n];
                    return {
                        publicKey: e,
                        secretKey: new Uint8Array(t)
                    }
                }, t.sign.keyPair.fromSeed = function(t) {
                    if (Q(t), t.length !== Ut) throw new Error("bad seed size");
                    for (var e = new Uint8Array(Ot), n = new Uint8Array(Mt), r = 0; r < 32; r++) n[r] = t[r];
                    return F(e, n, !0), {
                        publicKey: e,
                        secretKey: n
                    }
                }, t.sign.publicKeyLength = Ot, t.sign.secretKeyLength = Mt, t.sign.seedLength = Ut, t.sign.signatureLength = Rt, t.hash = function(t) {
                    Q(t);
                    var e = new Uint8Array(Lt);
                    return D(e, t, t.length), e
                }, t.hash.hashLength = Lt, t.verify = function(t, e) {
                    return Q(t, e), 0 !== t.length && 0 !== e.length && (t.length === e.length && 0 === r(t, 0, e, 0, t.length))
                }, t.setPRNG = function(t) {
                    et = t
                },
                function() {
                    var e = "undefined" != typeof self ? self.crypto || self.msCrypto : null;
                    if (e && e.getRandomValues) {
                        var r = 65536;
                        t.setPRNG(function(t, n) {
                            var i, o = new Uint8Array(n);
                            for (i = 0; i < n; i += r) e.getRandomValues(o.subarray(i, i + Math.min(n - i, r)));
                            for (i = 0; i < n; i++) t[i] = o[i];
                            $(o)
                        })
                    } else e = n(56), e && e.randomBytes && t.setPRNG(function(t, n) {
                        var r, i = e.randomBytes(n);
                        for (r = 0; r < n; r++) t[r] = i[r];
                        $(i)
                    })
                }()
        }("undefined" != typeof t && t.exports ? t.exports : self.nacl = self.nacl || {})
    }, function(t, e) {}, function(t, e, n) {
        (function(e) {
            ! function(e, n) {
                "use strict";
                "undefined" != typeof t && t.exports ? t.exports = n() : e.nacl ? e.nacl.util = n() : (e.nacl = {}, e.nacl.util = n())
            }(this, function() {
                "use strict";

                function t(t) {
                    if (!/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?$/.test(t)) throw new TypeError("invalid encoding")
                }
                var n = {};
                return n.decodeUTF8 = function(t) {
                    if ("string" != typeof t) throw new TypeError("expected string");
                    var e, n = unescape(encodeURIComponent(t)),
                        r = new Uint8Array(n.length);
                    for (e = 0; e < n.length; e++) r[e] = n.charCodeAt(e);
                    return r
                }, n.encodeUTF8 = function(t) {
                    var e, n = [];
                    for (e = 0; e < t.length; e++) n.push(String.fromCharCode(t[e]));
                    return decodeURIComponent(escape(n.join("")))
                }, "undefined" == typeof atob ? "undefined" != typeof e.from ? (n.encodeBase64 = function(t) {
                    return e.from(t).toString("base64")
                }, n.decodeBase64 = function(n) {
                    return t(n), new Uint8Array(Array.prototype.slice.call(e.from(n, "base64"), 0))
                }) : (n.encodeBase64 = function(t) {
                    return new e(t).toString("base64")
                }, n.decodeBase64 = function(n) {
                    return t(n), new Uint8Array(Array.prototype.slice.call(new e(n, "base64"), 0))
                }) : (n.encodeBase64 = function(t) {
                    var e, n = [],
                        r = t.length;
                    for (e = 0; e < r; e++) n.push(String.fromCharCode(t[e]));
                    return btoa(n.join(""))
                }, n.decodeBase64 = function(e) {
                    t(e);
                    var n, r = atob(e),
                        i = new Uint8Array(r.length);
                    for (n = 0; n < r.length; n++) i[n] = r.charCodeAt(n);
                    return i
                }), n
            })
        }).call(e, n(58).Buffer)
    }, function(t, e, n) {
        /*!

        	 * The buffer module from node.js, for the browser.

        	 *

        	 * @author   Feross Aboukhadijeh <feross@feross.org> <http://feross.org>

        	 * @license  MIT

        	 */

        "use strict";

        function r() {
            try {
                var t = new Uint8Array(1);
                return t.__proto__ = {
                    __proto__: Uint8Array.prototype,
                    foo: function() {
                        return 42
                    }
                }, 42 === t.foo() && "function" == typeof t.subarray && 0 === t.subarray(1, 1).byteLength
            } catch (t) {
                return !1
            }
        }

        function i() {
            return s.TYPED_ARRAY_SUPPORT ? 2147483647 : 1073741823
        }

        function o(t, e) {
            if (i() < e) throw new RangeError("Invalid typed array length");
            return s.TYPED_ARRAY_SUPPORT ? (t = new Uint8Array(e), t.__proto__ = s.prototype) : (null === t && (t = new s(e)), t.length = e), t
        }

        function s(t, e, n) {
            if (!(s.TYPED_ARRAY_SUPPORT || this instanceof s)) return new s(t, e, n);
            if ("number" == typeof t) {
                if ("string" == typeof e) throw new Error("If encoding is specified then the first argument must be a string");
                return h(this, t)
            }
            return a(this, t, e, n)
        }

        function a(t, e, n, r) {
            if ("number" == typeof e) throw new TypeError('"value" argument must not be a number');
            return "undefined" != typeof ArrayBuffer && e instanceof ArrayBuffer ? p(t, e, n, r) : "string" == typeof e ? f(t, e, n) : d(t, e)
        }

        function u(t) {
            if ("number" != typeof t) throw new TypeError('"size" argument must be a number');
            if (t < 0) throw new RangeError('"size" argument must not be negative')
        }

        function c(t, e, n, r) {
            return u(e), e <= 0 ? o(t, e) : void 0 !== n ? "string" == typeof r ? o(t, e).fill(n, r) : o(t, e).fill(n) : o(t, e)
        }

        function h(t, e) {
            if (u(e), t = o(t, e < 0 ? 0 : 0 | y(e)), !s.TYPED_ARRAY_SUPPORT)
                for (var n = 0; n < e; ++n) t[n] = 0;
            return t
        }

        function f(t, e, n) {
            if ("string" == typeof n && "" !== n || (n = "utf8"), !s.isEncoding(n)) throw new TypeError('"encoding" must be a valid string encoding');
            var r = 0 | v(e, n);
            t = o(t, r);
            var i = t.write(e, n);
            return i !== r && (t = t.slice(0, i)), t
        }

        function l(t, e) {
            var n = e.length < 0 ? 0 : 0 | y(e.length);
            t = o(t, n);
            for (var r = 0; r < n; r += 1) t[r] = 255 & e[r];
            return t
        }

        function p(t, e, n, r) {
            if (e.byteLength, n < 0 || e.byteLength < n) throw new RangeError("'offset' is out of bounds");
            if (e.byteLength < n + (r || 0)) throw new RangeError("'length' is out of bounds");
            return e = void 0 === n && void 0 === r ? new Uint8Array(e) : void 0 === r ? new Uint8Array(e, n) : new Uint8Array(e, n, r), s.TYPED_ARRAY_SUPPORT ? (t = e, t.__proto__ = s.prototype) : t = l(t, e), t
        }

        function d(t, e) {
            if (s.isBuffer(e)) {
                var n = 0 | y(e.length);
                return t = o(t, n), 0 === t.length ? t : (e.copy(t, 0, 0, n), t)
            }
            if (e) {
                if ("undefined" != typeof ArrayBuffer && e.buffer instanceof ArrayBuffer || "length" in e) return "number" != typeof e.length || V(e.length) ? o(t, 0) : l(t, e);
                if ("Buffer" === e.type && $(e.data)) return l(t, e.data)
            }
            throw new TypeError("First argument must be a string, Buffer, ArrayBuffer, Array, or array-like object.")
        }

        function y(t) {
            if (t >= i()) throw new RangeError("Attempt to allocate Buffer larger than maximum size: 0x" + i().toString(16) + " bytes");
            return 0 | t
        }

        function g(t) {
            return +t != t && (t = 0), s.alloc(+t)
        }

        function v(t, e) {
            if (s.isBuffer(t)) return t.length;
            if ("undefined" != typeof ArrayBuffer && "function" == typeof ArrayBuffer.isView && (ArrayBuffer.isView(t) || t instanceof ArrayBuffer)) return t.byteLength;
            "string" != typeof t && (t = "" + t);
            var n = t.length;
            if (0 === n) return 0;
            for (var r = !1;;) switch (e) {
                case "ascii":
                case "latin1":
                case "binary":
                    return n;
                case "utf8":
                case "utf-8":
                case void 0:
                    return J(t).length;
                case "ucs2":
                case "ucs-2":
                case "utf16le":
                case "utf-16le":
                    return 2 * n;
                case "hex":
                    return n >>> 1;
                case "base64":
                    return G(t).length;
                default:
                    if (r) return J(t).length;
                    e = ("" + e).toLowerCase(), r = !0
            }
        }

        function b(t, e, n) {
            var r = !1;
            if ((void 0 === e || e < 0) && (e = 0), e > this.length) return "";
            if ((void 0 === n || n > this.length) && (n = this.length), n <= 0) return "";
            if (n >>>= 0, e >>>= 0, n <= e) return "";
            for (t || (t = "utf8");;) switch (t) {
                case "hex":
                    return U(this, e, n);
                case "utf8":
                case "utf-8":
                    return P(this, e, n);
                case "ascii":
                    return O(this, e, n);
                case "latin1":
                case "binary":
                    return M(this, e, n);
                case "base64":
                    return C(this, e, n);
                case "ucs2":
                case "ucs-2":
                case "utf16le":
                case "utf-16le":
                    return L(this, e, n);
                default:
                    if (r) throw new TypeError("Unknown encoding: " + t);
                    t = (t + "").toLowerCase(), r = !0
            }
        }

        function m(t, e, n) {
            var r = t[e];
            t[e] = t[n], t[n] = r
        }

        function w(t, e, n, r, i) {
            if (0 === t.length) return -1;
            if ("string" == typeof n ? (r = n, n = 0) : n > 2147483647 ? n = 2147483647 : n < -2147483648 && (n = -2147483648), n = +n, isNaN(n) && (n = i ? 0 : t.length - 1), n < 0 && (n = t.length + n), n >= t.length) {
                if (i) return -1;
                n = t.length - 1
            } else if (n < 0) {
                if (!i) return -1;
                n = 0
            }
            if ("string" == typeof e && (e = s.from(e, r)), s.isBuffer(e)) return 0 === e.length ? -1 : _(t, e, n, r, i);
            if ("number" == typeof e) return e &= 255, s.TYPED_ARRAY_SUPPORT && "function" == typeof Uint8Array.prototype.indexOf ? i ? Uint8Array.prototype.indexOf.call(t, e, n) : Uint8Array.prototype.lastIndexOf.call(t, e, n) : _(t, [e], n, r, i);
            throw new TypeError("val must be string, number or Buffer")
        }

        function _(t, e, n, r, i) {
            function o(t, e) {
                return 1 === s ? t[e] : t.readUInt16BE(e * s)
            }
            var s = 1,
                a = t.length,
                u = e.length;
            if (void 0 !== r && (r = String(r).toLowerCase(), "ucs2" === r || "ucs-2" === r || "utf16le" === r || "utf-16le" === r)) {
                if (t.length < 2 || e.length < 2) return -1;
                s = 2, a /= 2, u /= 2, n /= 2
            }
            var c;
            if (i) {
                var h = -1;
                for (c = n; c < a; c++)
                    if (o(t, c) === o(e, h === -1 ? 0 : c - h)) {
                        if (h === -1 && (h = c), c - h + 1 === u) return h * s
                    } else h !== -1 && (c -= c - h), h = -1
            } else
                for (n + u > a && (n = a - u), c = n; c >= 0; c--) {
                    for (var f = !0, l = 0; l < u; l++)
                        if (o(t, c + l) !== o(e, l)) {
                            f = !1;
                            break
                        } if (f) return c
                }
            return -1
        }

        function S(t, e, n, r) {
            n = Number(n) || 0;
            var i = t.length - n;
            r ? (r = Number(r), r > i && (r = i)) : r = i;
            var o = e.length;
            if (o % 2 !== 0) throw new TypeError("Invalid hex string");
            r > o / 2 && (r = o / 2);
            for (var s = 0; s < r; ++s) {
                var a = parseInt(e.substr(2 * s, 2), 16);
                if (isNaN(a)) return s;
                t[n + s] = a
            }
            return s
        }

        function k(t, e, n, r) {
            return W(J(e, t.length - n), t, n, r)
        }

        function T(t, e, n, r) {
            return W(X(e), t, n, r)
        }

        function A(t, e, n, r) {
            return T(t, e, n, r)
        }

        function E(t, e, n, r) {
            return W(G(e), t, n, r)
        }

        function x(t, e, n, r) {
            return W(K(e, t.length - n), t, n, r)
        }

        function C(t, e, n) {
            return 0 === e && n === t.length ? Z.fromByteArray(t) : Z.fromByteArray(t.slice(e, n))
        }

        function P(t, e, n) {
            n = Math.min(t.length, n);
            for (var r = [], i = e; i < n;) {
                var o = t[i],
                    s = null,
                    a = o > 239 ? 4 : o > 223 ? 3 : o > 191 ? 2 : 1;
                if (i + a <= n) {
                    var u, c, h, f;
                    switch (a) {
                        case 1:
                            o < 128 && (s = o);
                            break;
                        case 2:
                            u = t[i + 1], 128 === (192 & u) && (f = (31 & o) << 6 | 63 & u, f > 127 && (s = f));
                            break;
                        case 3:
                            u = t[i + 1], c = t[i + 2], 128 === (192 & u) && 128 === (192 & c) && (f = (15 & o) << 12 | (63 & u) << 6 | 63 & c, f > 2047 && (f < 55296 || f > 57343) && (s = f));
                            break;
                        case 4:
                            u = t[i + 1], c = t[i + 2], h = t[i + 3], 128 === (192 & u) && 128 === (192 & c) && 128 === (192 & h) && (f = (15 & o) << 18 | (63 & u) << 12 | (63 & c) << 6 | 63 & h, f > 65535 && f < 1114112 && (s = f))
                    }
                }
                null === s ? (s = 65533, a = 1) : s > 65535 && (s -= 65536, r.push(s >>> 10 & 1023 | 55296), s = 56320 | 1023 & s), r.push(s), i += a
            }
            return R(r)
        }

        function R(t) {
            var e = t.length;
            if (e <= tt) return String.fromCharCode.apply(String, t);
            for (var n = "", r = 0; r < e;) n += String.fromCharCode.apply(String, t.slice(r, r += tt));
            return n
        }

        function O(t, e, n) {
            var r = "";
            n = Math.min(t.length, n);
            for (var i = e; i < n; ++i) r += String.fromCharCode(127 & t[i]);
            return r
        }

        function M(t, e, n) {
            var r = "";
            n = Math.min(t.length, n);
            for (var i = e; i < n; ++i) r += String.fromCharCode(t[i]);
            return r
        }

        function U(t, e, n) {
            var r = t.length;
            (!e || e < 0) && (e = 0), (!n || n < 0 || n > r) && (n = r);
            for (var i = "", o = e; o < n; ++o) i += F(t[o]);
            return i
        }

        function L(t, e, n) {
            for (var r = t.slice(e, n), i = "", o = 0; o < r.length; o += 2) i += String.fromCharCode(r[o] + 256 * r[o + 1]);
            return i
        }

        function B(t, e, n) {
            if (t % 1 !== 0 || t < 0) throw new RangeError("offset is not uint");
            if (t + e > n) throw new RangeError("Trying to access beyond buffer length")
        }

        function I(t, e, n, r, i, o) {
            if (!s.isBuffer(t)) throw new TypeError('"buffer" argument must be a Buffer instance');
            if (e > i || e < o) throw new RangeError('"value" argument is out of bounds');
            if (n + r > t.length) throw new RangeError("Index out of range")
        }

        function N(t, e, n, r) {
            e < 0 && (e = 65535 + e + 1);
            for (var i = 0, o = Math.min(t.length - n, 2); i < o; ++i) t[n + i] = (e & 255 << 8 * (r ? i : 1 - i)) >>> 8 * (r ? i : 1 - i)
        }

        function D(t, e, n, r) {
            e < 0 && (e = 4294967295 + e + 1);
            for (var i = 0, o = Math.min(t.length - n, 4); i < o; ++i) t[n + i] = e >>> 8 * (r ? i : 3 - i) & 255
        }

        function j(t, e, n, r, i, o) {
            if (n + r > t.length) throw new RangeError("Index out of range");
            if (n < 0) throw new RangeError("Index out of range")
        }

        function Y(t, e, n, r, i) {
            return i || j(t, e, n, 4, 3.4028234663852886e38, -3.4028234663852886e38), Q.write(t, e, n, r, 23, 4), n + 4
        }

        function z(t, e, n, r, i) {
            return i || j(t, e, n, 8, 1.7976931348623157e308, -1.7976931348623157e308), Q.write(t, e, n, r, 52, 8), n + 8
        }

        function H(t) {
            if (t = q(t).replace(et, ""), t.length < 2) return "";
            for (; t.length % 4 !== 0;) t += "=";
            return t
        }

        function q(t) {
            return t.trim ? t.trim() : t.replace(/^\s+|\s+$/g, "")
        }

        function F(t) {
            return t < 16 ? "0" + t.toString(16) : t.toString(16)
        }

        function J(t, e) {
            e = e || 1 / 0;
            for (var n, r = t.length, i = null, o = [], s = 0; s < r; ++s) {
                if (n = t.charCodeAt(s), n > 55295 && n < 57344) {
                    if (!i) {
                        if (n > 56319) {
                            (e -= 3) > -1 && o.push(239, 191, 189);
                            continue
                        }
                        if (s + 1 === r) {
                            (e -= 3) > -1 && o.push(239, 191, 189);
                            continue
                        }
                        i = n;
                        continue
                    }
                    if (n < 56320) {
                        (e -= 3) > -1 && o.push(239, 191, 189), i = n;
                        continue
                    }
                    n = (i - 55296 << 10 | n - 56320) + 65536
                } else i && (e -= 3) > -1 && o.push(239, 191, 189);
                if (i = null, n < 128) {
                    if ((e -= 1) < 0) break;
                    o.push(n)
                } else if (n < 2048) {
                    if ((e -= 2) < 0) break;
                    o.push(n >> 6 | 192, 63 & n | 128)
                } else if (n < 65536) {
                    if ((e -= 3) < 0) break;
                    o.push(n >> 12 | 224, n >> 6 & 63 | 128, 63 & n | 128)
                } else {
                    if (!(n < 1114112)) throw new Error("Invalid code point");
                    if ((e -= 4) < 0) break;
                    o.push(n >> 18 | 240, n >> 12 & 63 | 128, n >> 6 & 63 | 128, 63 & n | 128)
                }
            }
            return o
        }

        function X(t) {
            for (var e = [], n = 0; n < t.length; ++n) e.push(255 & t.charCodeAt(n));
            return e
        }

        function K(t, e) {
            for (var n, r, i, o = [], s = 0; s < t.length && !((e -= 2) < 0); ++s) n = t.charCodeAt(s), r = n >> 8, i = n % 256, o.push(i), o.push(r);
            return o
        }

        function G(t) {
            return Z.toByteArray(H(t))
        }

        function W(t, e, n, r) {
            for (var i = 0; i < r && !(i + n >= e.length || i >= t.length); ++i) e[i + n] = t[i];
            return i
        }

        function V(t) {
            return t !== t
        }
        var Z = n(59),
            Q = n(60),
            $ = n(61);
        e.Buffer = s, e.SlowBuffer = g, e.INSPECT_MAX_BYTES = 50, s.TYPED_ARRAY_SUPPORT = void 0 !== window.TYPED_ARRAY_SUPPORT ? window.TYPED_ARRAY_SUPPORT : r(), e.kMaxLength = i(), s.poolSize = 8192, s._augment = function(t) {
            return t.__proto__ = s.prototype, t
        }, s.from = function(t, e, n) {
            return a(null, t, e, n)
        }, s.TYPED_ARRAY_SUPPORT && (s.prototype.__proto__ = Uint8Array.prototype, s.__proto__ = Uint8Array, "undefined" != typeof Symbol && Symbol.species && s[Symbol.species] === s && Object.defineProperty(s, Symbol.species, {
            value: null,
            configurable: !0
        })), s.alloc = function(t, e, n) {
            return c(null, t, e, n)
        }, s.allocUnsafe = function(t) {
            return h(null, t)
        }, s.allocUnsafeSlow = function(t) {
            return h(null, t)
        }, s.isBuffer = function(t) {
            return !(null == t || !t._isBuffer)
        }, s.compare = function(t, e) {
            if (!s.isBuffer(t) || !s.isBuffer(e)) throw new TypeError("Arguments must be Buffers");
            if (t === e) return 0;
            for (var n = t.length, r = e.length, i = 0, o = Math.min(n, r); i < o; ++i)
                if (t[i] !== e[i]) {
                    n = t[i], r = e[i];
                    break
                } return n < r ? -1 : r < n ? 1 : 0
        }, s.isEncoding = function(t) {
            switch (String(t).toLowerCase()) {
                case "hex":
                case "utf8":
                case "utf-8":
                case "ascii":
                case "latin1":
                case "binary":
                case "base64":
                case "ucs2":
                case "ucs-2":
                case "utf16le":
                case "utf-16le":
                    return !0;
                default:
                    return !1
            }
        }, s.concat = function(t, e) {
            if (!$(t)) throw new TypeError('"list" argument must be an Array of Buffers');
            if (0 === t.length) return s.alloc(0);
            var n;
            if (void 0 === e)
                for (e = 0, n = 0; n < t.length; ++n) e += t[n].length;
            var r = s.allocUnsafe(e),
                i = 0;
            for (n = 0; n < t.length; ++n) {
                var o = t[n];
                if (!s.isBuffer(o)) throw new TypeError('"list" argument must be an Array of Buffers');
                o.copy(r, i), i += o.length
            }
            return r
        }, s.byteLength = v, s.prototype._isBuffer = !0, s.prototype.swap16 = function() {
            var t = this.length;
            if (t % 2 !== 0) throw new RangeError("Buffer size must be a multiple of 16-bits");
            for (var e = 0; e < t; e += 2) m(this, e, e + 1);
            return this
        }, s.prototype.swap32 = function() {
            var t = this.length;
            if (t % 4 !== 0) throw new RangeError("Buffer size must be a multiple of 32-bits");
            for (var e = 0; e < t; e += 4) m(this, e, e + 3), m(this, e + 1, e + 2);
            return this
        }, s.prototype.swap64 = function() {
            var t = this.length;
            if (t % 8 !== 0) throw new RangeError("Buffer size must be a multiple of 64-bits");
            for (var e = 0; e < t; e += 8) m(this, e, e + 7), m(this, e + 1, e + 6), m(this, e + 2, e + 5), m(this, e + 3, e + 4);
            return this
        }, s.prototype.toString = function() {
            var t = 0 | this.length;
            return 0 === t ? "" : 0 === arguments.length ? P(this, 0, t) : b.apply(this, arguments)
        }, s.prototype.equals = function(t) {
            if (!s.isBuffer(t)) throw new TypeError("Argument must be a Buffer");
            return this === t || 0 === s.compare(this, t)
        }, s.prototype.inspect = function() {
            var t = "",
                n = e.INSPECT_MAX_BYTES;
            return this.length > 0 && (t = this.toString("hex", 0, n).match(/.{2}/g).join(" "), this.length > n && (t += " ... ")), "<Buffer " + t + ">"
        }, s.prototype.compare = function(t, e, n, r, i) {
            if (!s.isBuffer(t)) throw new TypeError("Argument must be a Buffer");
            if (void 0 === e && (e = 0), void 0 === n && (n = t ? t.length : 0), void 0 === r && (r = 0), void 0 === i && (i = this.length), e < 0 || n > t.length || r < 0 || i > this.length) throw new RangeError("out of range index");
            if (r >= i && e >= n) return 0;
            if (r >= i) return -1;
            if (e >= n) return 1;
            if (e >>>= 0, n >>>= 0, r >>>= 0, i >>>= 0, this === t) return 0;
            for (var o = i - r, a = n - e, u = Math.min(o, a), c = this.slice(r, i), h = t.slice(e, n), f = 0; f < u; ++f)
                if (c[f] !== h[f]) {
                    o = c[f], a = h[f];
                    break
                } return o < a ? -1 : a < o ? 1 : 0
        }, s.prototype.includes = function(t, e, n) {
            return this.indexOf(t, e, n) !== -1
        }, s.prototype.indexOf = function(t, e, n) {
            return w(this, t, e, n, !0)
        }, s.prototype.lastIndexOf = function(t, e, n) {
            return w(this, t, e, n, !1)
        }, s.prototype.write = function(t, e, n, r) {
            if (void 0 === e) r = "utf8", n = this.length, e = 0;
            else if (void 0 === n && "string" == typeof e) r = e, n = this.length, e = 0;
            else {
                if (!isFinite(e)) throw new Error("Buffer.write(string, encoding, offset[, length]) is no longer supported");
                e |= 0, isFinite(n) ? (n |= 0, void 0 === r && (r = "utf8")) : (r = n, n = void 0)
            }
            var i = this.length - e;
            if ((void 0 === n || n > i) && (n = i), t.length > 0 && (n < 0 || e < 0) || e > this.length) throw new RangeError("Attempt to write outside buffer bounds");
            r || (r = "utf8");
            for (var o = !1;;) switch (r) {
                case "hex":
                    return S(this, t, e, n);
                case "utf8":
                case "utf-8":
                    return k(this, t, e, n);
                case "ascii":
                    return T(this, t, e, n);
                case "latin1":
                case "binary":
                    return A(this, t, e, n);
                case "base64":
                    return E(this, t, e, n);
                case "ucs2":
                case "ucs-2":
                case "utf16le":
                case "utf-16le":
                    return x(this, t, e, n);
                default:
                    if (o) throw new TypeError("Unknown encoding: " + r);
                    r = ("" + r).toLowerCase(), o = !0
            }
        }, s.prototype.toJSON = function() {
            return {
                type: "Buffer",
                data: Array.prototype.slice.call(this._arr || this, 0)
            }
        };
        var tt = 4096;
        s.prototype.slice = function(t, e) {
            var n = this.length;
            t = ~~t, e = void 0 === e ? n : ~~e, t < 0 ? (t += n, t < 0 && (t = 0)) : t > n && (t = n), e < 0 ? (e += n, e < 0 && (e = 0)) : e > n && (e = n), e < t && (e = t);
            var r;
            if (s.TYPED_ARRAY_SUPPORT) r = this.subarray(t, e), r.__proto__ = s.prototype;
            else {
                var i = e - t;
                r = new s(i, void 0);
                for (var o = 0; o < i; ++o) r[o] = this[o + t]
            }
            return r
        }, s.prototype.readUIntLE = function(t, e, n) {
            t |= 0, e |= 0, n || B(t, e, this.length);
            for (var r = this[t], i = 1, o = 0; ++o < e && (i *= 256);) r += this[t + o] * i;
            return r
        }, s.prototype.readUIntBE = function(t, e, n) {
            t |= 0, e |= 0, n || B(t, e, this.length);
            for (var r = this[t + --e], i = 1; e > 0 && (i *= 256);) r += this[t + --e] * i;
            return r
        }, s.prototype.readUInt8 = function(t, e) {
            return e || B(t, 1, this.length), this[t]
        }, s.prototype.readUInt16LE = function(t, e) {
            return e || B(t, 2, this.length), this[t] | this[t + 1] << 8
        }, s.prototype.readUInt16BE = function(t, e) {
            return e || B(t, 2, this.length), this[t] << 8 | this[t + 1]
        }, s.prototype.readUInt32LE = function(t, e) {
            return e || B(t, 4, this.length), (this[t] | this[t + 1] << 8 | this[t + 2] << 16) + 16777216 * this[t + 3]
        }, s.prototype.readUInt32BE = function(t, e) {
            return e || B(t, 4, this.length), 16777216 * this[t] + (this[t + 1] << 16 | this[t + 2] << 8 | this[t + 3])
        }, s.prototype.readIntLE = function(t, e, n) {
            t |= 0, e |= 0, n || B(t, e, this.length);
            for (var r = this[t], i = 1, o = 0; ++o < e && (i *= 256);) r += this[t + o] * i;
            return i *= 128, r >= i && (r -= Math.pow(2, 8 * e)), r
        }, s.prototype.readIntBE = function(t, e, n) {
            t |= 0, e |= 0, n || B(t, e, this.length);
            for (var r = e, i = 1, o = this[t + --r]; r > 0 && (i *= 256);) o += this[t + --r] * i;
            return i *= 128, o >= i && (o -= Math.pow(2, 8 * e)), o
        }, s.prototype.readInt8 = function(t, e) {
            return e || B(t, 1, this.length), 128 & this[t] ? (255 - this[t] + 1) * -1 : this[t]
        }, s.prototype.readInt16LE = function(t, e) {
            e || B(t, 2, this.length);
            var n = this[t] | this[t + 1] << 8;
            return 32768 & n ? 4294901760 | n : n
        }, s.prototype.readInt16BE = function(t, e) {
            e || B(t, 2, this.length);
            var n = this[t + 1] | this[t] << 8;
            return 32768 & n ? 4294901760 | n : n
        }, s.prototype.readInt32LE = function(t, e) {
            return e || B(t, 4, this.length), this[t] | this[t + 1] << 8 | this[t + 2] << 16 | this[t + 3] << 24
        }, s.prototype.readInt32BE = function(t, e) {
            return e || B(t, 4, this.length), this[t] << 24 | this[t + 1] << 16 | this[t + 2] << 8 | this[t + 3]
        }, s.prototype.readFloatLE = function(t, e) {
            return e || B(t, 4, this.length), Q.read(this, t, !0, 23, 4)
        }, s.prototype.readFloatBE = function(t, e) {
            return e || B(t, 4, this.length), Q.read(this, t, !1, 23, 4)
        }, s.prototype.readDoubleLE = function(t, e) {
            return e || B(t, 8, this.length), Q.read(this, t, !0, 52, 8)
        }, s.prototype.readDoubleBE = function(t, e) {
            return e || B(t, 8, this.length), Q.read(this, t, !1, 52, 8)
        }, s.prototype.writeUIntLE = function(t, e, n, r) {
            if (t = +t, e |= 0, n |= 0, !r) {
                var i = Math.pow(2, 8 * n) - 1;
                I(this, t, e, n, i, 0)
            }
            var o = 1,
                s = 0;
            for (this[e] = 255 & t; ++s < n && (o *= 256);) this[e + s] = t / o & 255;
            return e + n
        }, s.prototype.writeUIntBE = function(t, e, n, r) {
            if (t = +t, e |= 0, n |= 0, !r) {
                var i = Math.pow(2, 8 * n) - 1;
                I(this, t, e, n, i, 0)
            }
            var o = n - 1,
                s = 1;
            for (this[e + o] = 255 & t; --o >= 0 && (s *= 256);) this[e + o] = t / s & 255;
            return e + n
        }, s.prototype.writeUInt8 = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 1, 255, 0), s.TYPED_ARRAY_SUPPORT || (t = Math.floor(t)), this[e] = 255 & t, e + 1
        }, s.prototype.writeUInt16LE = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 2, 65535, 0), s.TYPED_ARRAY_SUPPORT ? (this[e] = 255 & t, this[e + 1] = t >>> 8) : N(this, t, e, !0), e + 2
        }, s.prototype.writeUInt16BE = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 2, 65535, 0), s.TYPED_ARRAY_SUPPORT ? (this[e] = t >>> 8, this[e + 1] = 255 & t) : N(this, t, e, !1), e + 2
        }, s.prototype.writeUInt32LE = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 4, 4294967295, 0), s.TYPED_ARRAY_SUPPORT ? (this[e + 3] = t >>> 24, this[e + 2] = t >>> 16, this[e + 1] = t >>> 8, this[e] = 255 & t) : D(this, t, e, !0), e + 4
        }, s.prototype.writeUInt32BE = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 4, 4294967295, 0), s.TYPED_ARRAY_SUPPORT ? (this[e] = t >>> 24, this[e + 1] = t >>> 16, this[e + 2] = t >>> 8, this[e + 3] = 255 & t) : D(this, t, e, !1), e + 4
        }, s.prototype.writeIntLE = function(t, e, n, r) {
            if (t = +t, e |= 0, !r) {
                var i = Math.pow(2, 8 * n - 1);
                I(this, t, e, n, i - 1, -i)
            }
            var o = 0,
                s = 1,
                a = 0;
            for (this[e] = 255 & t; ++o < n && (s *= 256);) t < 0 && 0 === a && 0 !== this[e + o - 1] && (a = 1), this[e + o] = (t / s >> 0) - a & 255;
            return e + n
        }, s.prototype.writeIntBE = function(t, e, n, r) {
            if (t = +t, e |= 0, !r) {
                var i = Math.pow(2, 8 * n - 1);
                I(this, t, e, n, i - 1, -i)
            }
            var o = n - 1,
                s = 1,
                a = 0;
            for (this[e + o] = 255 & t; --o >= 0 && (s *= 256);) t < 0 && 0 === a && 0 !== this[e + o + 1] && (a = 1), this[e + o] = (t / s >> 0) - a & 255;
            return e + n
        }, s.prototype.writeInt8 = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 1, 127, -128), s.TYPED_ARRAY_SUPPORT || (t = Math.floor(t)), t < 0 && (t = 255 + t + 1), this[e] = 255 & t, e + 1
        }, s.prototype.writeInt16LE = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 2, 32767, -32768), s.TYPED_ARRAY_SUPPORT ? (this[e] = 255 & t, this[e + 1] = t >>> 8) : N(this, t, e, !0), e + 2
        }, s.prototype.writeInt16BE = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 2, 32767, -32768), s.TYPED_ARRAY_SUPPORT ? (this[e] = t >>> 8, this[e + 1] = 255 & t) : N(this, t, e, !1), e + 2
        }, s.prototype.writeInt32LE = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 4, 2147483647, -2147483648), s.TYPED_ARRAY_SUPPORT ? (this[e] = 255 & t, this[e + 1] = t >>> 8, this[e + 2] = t >>> 16, this[e + 3] = t >>> 24) : D(this, t, e, !0), e + 4
        }, s.prototype.writeInt32BE = function(t, e, n) {
            return t = +t, e |= 0, n || I(this, t, e, 4, 2147483647, -2147483648), t < 0 && (t = 4294967295 + t + 1), s.TYPED_ARRAY_SUPPORT ? (this[e] = t >>> 24, this[e + 1] = t >>> 16, this[e + 2] = t >>> 8, this[e + 3] = 255 & t) : D(this, t, e, !1), e + 4
        }, s.prototype.writeFloatLE = function(t, e, n) {
            return Y(this, t, e, !0, n)
        }, s.prototype.writeFloatBE = function(t, e, n) {
            return Y(this, t, e, !1, n)
        }, s.prototype.writeDoubleLE = function(t, e, n) {
            return z(this, t, e, !0, n)
        }, s.prototype.writeDoubleBE = function(t, e, n) {
            return z(this, t, e, !1, n)
        }, s.prototype.copy = function(t, e, n, r) {
            if (n || (n = 0), r || 0 === r || (r = this.length), e >= t.length && (e = t.length), e || (e = 0), r > 0 && r < n && (r = n), r === n) return 0;
            if (0 === t.length || 0 === this.length) return 0;
            if (e < 0) throw new RangeError("targetStart out of bounds");
            if (n < 0 || n >= this.length) throw new RangeError("sourceStart out of bounds");
            if (r < 0) throw new RangeError("sourceEnd out of bounds");
            r > this.length && (r = this.length), t.length - e < r - n && (r = t.length - e + n);
            var i, o = r - n;
            if (this === t && n < e && e < r)
                for (i = o - 1; i >= 0; --i) t[i + e] = this[i + n];
            else if (o < 1e3 || !s.TYPED_ARRAY_SUPPORT)
                for (i = 0; i < o; ++i) t[i + e] = this[i + n];
            else Uint8Array.prototype.set.call(t, this.subarray(n, n + o), e);
            return o
        }, s.prototype.fill = function(t, e, n, r) {
            if ("string" == typeof t) {
                if ("string" == typeof e ? (r = e, e = 0, n = this.length) : "string" == typeof n && (r = n, n = this.length), 1 === t.length) {
                    var i = t.charCodeAt(0);
                    i < 256 && (t = i)
                }
                if (void 0 !== r && "string" != typeof r) throw new TypeError("encoding must be a string");
                if ("string" == typeof r && !s.isEncoding(r)) throw new TypeError("Unknown encoding: " + r)
            } else "number" == typeof t && (t &= 255);
            if (e < 0 || this.length < e || this.length < n) throw new RangeError("Out of range index");
            if (n <= e) return this;
            e >>>= 0, n = void 0 === n ? this.length : n >>> 0, t || (t = 0);
            var o;
            if ("number" == typeof t)
                for (o = e; o < n; ++o) this[o] = t;
            else {
                var a = s.isBuffer(t) ? t : J(new s(t, r).toString()),
                    u = a.length;
                for (o = 0; o < n - e; ++o) this[o + e] = a[o % u]
            }
            return this
        };
        var et = /[^+\/0-9A-Za-z-_]/g
    }, function(t, e) {
        "use strict";

        function n(t) {
            var e = t.length;
            if (e % 4 > 0) throw new Error("Invalid string. Length must be a multiple of 4");
            var n = t.indexOf("=");
            n === -1 && (n = e);
            var r = n === e ? 0 : 4 - n % 4;
            return [n, r]
        }

        function r(t) {
            var e = n(t),
                r = e[0],
                i = e[1];
            return 3 * (r + i) / 4 - i
        }

        function i(t, e, n) {
            return 3 * (e + n) / 4 - n
        }

        function o(t) {
            for (var e, r = n(t), o = r[0], s = r[1], a = new f(i(t, o, s)), u = 0, c = s > 0 ? o - 4 : o, l = 0; l < c; l += 4) e = h[t.charCodeAt(l)] << 18 | h[t.charCodeAt(l + 1)] << 12 | h[t.charCodeAt(l + 2)] << 6 | h[t.charCodeAt(l + 3)], a[u++] = e >> 16 & 255, a[u++] = e >> 8 & 255, a[u++] = 255 & e;
            return 2 === s && (e = h[t.charCodeAt(l)] << 2 | h[t.charCodeAt(l + 1)] >> 4, a[u++] = 255 & e), 1 === s && (e = h[t.charCodeAt(l)] << 10 | h[t.charCodeAt(l + 1)] << 4 | h[t.charCodeAt(l + 2)] >> 2, a[u++] = e >> 8 & 255, a[u++] = 255 & e), a
        }

        function s(t) {
            return c[t >> 18 & 63] + c[t >> 12 & 63] + c[t >> 6 & 63] + c[63 & t]
        }

        function a(t, e, n) {
            for (var r, i = [], o = e; o < n; o += 3) r = (t[o] << 16 & 16711680) + (t[o + 1] << 8 & 65280) + (255 & t[o + 2]), i.push(s(r));
            return i.join("")
        }

        function u(t) {
            for (var e, n = t.length, r = n % 3, i = [], o = 16383, s = 0, u = n - r; s < u; s += o) i.push(a(t, s, s + o > u ? u : s + o));
            return 1 === r ? (e = t[n - 1], i.push(c[e >> 2] + c[e << 4 & 63] + "==")) : 2 === r && (e = (t[n - 2] << 8) + t[n - 1], i.push(c[e >> 10] + c[e >> 4 & 63] + c[e << 2 & 63] + "=")), i.join("")
        }
        e.byteLength = r, e.toByteArray = o, e.fromByteArray = u;
        for (var c = [], h = [], f = "undefined" != typeof Uint8Array ? Uint8Array : Array, l = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", p = 0, d = l.length; p < d; ++p) c[p] = l[p], h[l.charCodeAt(p)] = p;
        h["-".charCodeAt(0)] = 62, h["_".charCodeAt(0)] = 63
    }, function(t, e) {
        e.read = function(t, e, n, r, i) {
            var o, s, a = 8 * i - r - 1,
                u = (1 << a) - 1,
                c = u >> 1,
                h = -7,
                f = n ? i - 1 : 0,
                l = n ? -1 : 1,
                p = t[e + f];
            for (f += l, o = p & (1 << -h) - 1, p >>= -h, h += a; h > 0; o = 256 * o + t[e + f], f += l, h -= 8);
            for (s = o & (1 << -h) - 1, o >>= -h, h += r; h > 0; s = 256 * s + t[e + f], f += l, h -= 8);
            if (0 === o) o = 1 - c;
            else {
                if (o === u) return s ? NaN : (p ? -1 : 1) * (1 / 0);
                s += Math.pow(2, r), o -= c
            }
            return (p ? -1 : 1) * s * Math.pow(2, o - r)
        }, e.write = function(t, e, n, r, i, o) {
            var s, a, u, c = 8 * o - i - 1,
                h = (1 << c) - 1,
                f = h >> 1,
                l = 23 === i ? Math.pow(2, -24) - Math.pow(2, -77) : 0,
                p = r ? 0 : o - 1,
                d = r ? 1 : -1,
                y = e < 0 || 0 === e && 1 / e < 0 ? 1 : 0;
            for (e = Math.abs(e), isNaN(e) || e === 1 / 0 ? (a = isNaN(e) ? 1 : 0, s = h) : (s = Math.floor(Math.log(e) / Math.LN2), e * (u = Math.pow(2, -s)) < 1 && (s--, u *= 2), e += s + f >= 1 ? l / u : l * Math.pow(2, 1 - f), e * u >= 2 && (s++, u /= 2), s + f >= h ? (a = 0, s = h) : s + f >= 1 ? (a = (e * u - 1) * Math.pow(2, i), s += f) : (a = e * Math.pow(2, f - 1) * Math.pow(2, i), s = 0)); i >= 8; t[n + p] = 255 & a, p += d, a /= 256, i -= 8);
            for (s = s << i | a, c += i; c > 0; t[n + p] = 255 & s, p += d, s /= 256, c -= 8);
            t[n + p - d] |= 128 * y
        }
    }, function(t, e) {
        var n = {}.toString;
        t.exports = Array.isArray || function(t) {
            return "[object Array]" == n.call(t)
        }
    }, function(t, e, n) {
        "use strict";
        var r = this && this.__extends || function(t, e) {
                function n() {
                    this.constructor = t
                }
                for (var r in e) e.hasOwnProperty(r) && (t[r] = e[r]);
                t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n)
            },
            i = n(24),
            o = n(12),
            s = n(8),
            a = n(9),
            u = n(2),
            c = function(t) {
                function e(e, n) {
                    var r = this;
                    t.call(this), this.key = e, this.options = n || {}, this.state = "initialized", this.connection = null, this.usingTLS = !!n.useTLS, this.timeline = this.options.timeline, this.errorCallbacks = this.buildErrorCallbacks(), this.connectionCallbacks = this.buildConnectionCallbacks(this.errorCallbacks), this.handshakeCallbacks = this.buildHandshakeCallbacks(this.errorCallbacks);
                    var i = u.default.getNetwork();
                    i.bind("online", function() {
                        r.timeline.info({
                            netinfo: "online"
                        }), "connecting" !== r.state && "unavailable" !== r.state || r.retryIn(0)
                    }), i.bind("offline", function() {
                        r.timeline.info({
                            netinfo: "offline"
                        }), r.connection && r.sendActivityCheck()
                    }), this.updateStrategy()
                }
                return r(e, t), e.prototype.connect = function() {
                    if (!this.connection && !this.runner) {
                        if (!this.strategy.isSupported()) return void this.updateState("failed");
                        this.updateState("connecting"), this.startConnecting(), this.setUnavailableTimer()
                    }
                }, e.prototype.send = function(t) {
                    return !!this.connection && this.connection.send(t)
                }, e.prototype.send_event = function(t, e, n) {
                    return !!this.connection && this.connection.send_event(t, e, n)
                }, e.prototype.disconnect = function() {
                    this.disconnectInternally(), this.updateState("disconnected")
                }, e.prototype.isUsingTLS = function() {
                    return this.usingTLS
                }, e.prototype.startConnecting = function() {
                    var t = this,
                        e = function(n, r) {
                            n ? t.runner = t.strategy.connect(0, e) : "error" === r.action ? (t.emit("error", {
                                type: "HandshakeError",
                                error: r.error
                            }), t.timeline.error({
                                handshakeError: r.error
                            })) : (t.abortConnecting(), t.handshakeCallbacks[r.action](r))
                        };
                    this.runner = this.strategy.connect(0, e)
                }, e.prototype.abortConnecting = function() {
                    this.runner && (this.runner.abort(), this.runner = null)
                }, e.prototype.disconnectInternally = function() {
                    if (this.abortConnecting(), this.clearRetryTimer(), this.clearUnavailableTimer(), this.connection) {
                        var t = this.abandonConnection();
                        t.close()
                    }
                }, e.prototype.updateStrategy = function() {
                    this.strategy = this.options.getStrategy({
                        key: this.key,
                        timeline: this.timeline,
                        useTLS: this.usingTLS
                    })
                }, e.prototype.retryIn = function(t) {
                    var e = this;
                    this.timeline.info({
                        action: "retry",
                        delay: t
                    }), t > 0 && this.emit("connecting_in", Math.round(t / 1e3)), this.retryTimer = new o.OneOffTimer(t || 0, function() {
                        e.disconnectInternally(), e.connect()
                    })
                }, e.prototype.clearRetryTimer = function() {
                    this.retryTimer && (this.retryTimer.ensureAborted(), this.retryTimer = null)
                }, e.prototype.setUnavailableTimer = function() {
                    var t = this;
                    this.unavailableTimer = new o.OneOffTimer(this.options.unavailableTimeout, function() {
                        t.updateState("unavailable")
                    })
                }, e.prototype.clearUnavailableTimer = function() {
                    this.unavailableTimer && this.unavailableTimer.ensureAborted()
                }, e.prototype.sendActivityCheck = function() {
                    var t = this;
                    this.stopActivityCheck(), this.connection.ping(), this.activityTimer = new o.OneOffTimer(this.options.pongTimeout, function() {
                        t.timeline.error({
                            pong_timed_out: t.options.pongTimeout
                        }), t.retryIn(0)
                    })
                }, e.prototype.resetActivityCheck = function() {
                    var t = this;
                    this.stopActivityCheck(), this.connection && !this.connection.handlesActivityChecks() && (this.activityTimer = new o.OneOffTimer(this.activityTimeout, function() {
                        t.sendActivityCheck()
                    }))
                }, e.prototype.stopActivityCheck = function() {
                    this.activityTimer && this.activityTimer.ensureAborted()
                }, e.prototype.buildConnectionCallbacks = function(t) {
                    var e = this;
                    return a.extend({}, t, {
                        message: function(t) {
                            e.resetActivityCheck(), e.emit("message", t)
                        },
                        ping: function() {
                            e.send_event("pusher:pong", {})
                        },
                        activity: function() {
                            e.resetActivityCheck()
                        },
                        error: function(t) {
                            e.emit("error", {
                                type: "WebSocketError",
                                error: t
                            })
                        },
                        closed: function() {
                            e.abandonConnection(), e.shouldRetry() && e.retryIn(1e3)
                        }
                    })
                }, e.prototype.buildHandshakeCallbacks = function(t) {
                    var e = this;
                    return a.extend({}, t, {
                        connected: function(t) {
                            e.activityTimeout = Math.min(e.options.activityTimeout, t.activityTimeout, t.connection.activityTimeout || 1 / 0), e.clearUnavailableTimer(), e.setConnection(t.connection), e.socket_id = e.connection.id, e.updateState("connected", {
                                socket_id: e.socket_id
                            })
                        }
                    })
                }, e.prototype.buildErrorCallbacks = function() {
                    var t = this,
                        e = function(e) {
                            return function(n) {
                                n.error && t.emit("error", {
                                    type: "WebSocketError",
                                    error: n.error
                                }), e(n)
                            }
                        };
                    return {
                        tls_only: e(function() {
                            t.usingTLS = !0, t.updateStrategy(), t.retryIn(0)
                        }),
                        refused: e(function() {
                            t.disconnect()
                        }),
                        backoff: e(function() {
                            t.retryIn(1e3)
                        }),
                        retry: e(function() {
                            t.retryIn(0)
                        })
                    }
                }, e.prototype.setConnection = function(t) {
                    this.connection = t;
                    for (var e in this.connectionCallbacks) this.connection.bind(e, this.connectionCallbacks[e]);
                    this.resetActivityCheck()
                }, e.prototype.abandonConnection = function() {
                    if (this.connection) {
                        this.stopActivityCheck();
                        for (var t in this.connectionCallbacks) this.connection.unbind(t, this.connectionCallbacks[t]);
                        var e = this.connection;
                        return this.connection = null, e
                    }
                }, e.prototype.updateState = function(t, e) {
                    var n = this.state;
                    if (this.state = t, n !== t) {
                        var r = t;
                        "connected" === r && (r += " with new socket ID " + e.socket_id), s.default.debug("State changed", n + " -> " + r), this.timeline.info({
                            state: t,
                            params: e
                        }), this.emit("state_change", {
                            previous: n,
                            current: t
                        }), this.emit(t, e)
                    }
                }, e.prototype.shouldRetry = function() {
                    return "connecting" === this.state || "connected" === this.state
                }, e
            }(i.default);
        e.__esModule = !0, e.default = c
    }, function(t, e, n) {
        "use strict";

        function r(t, e) {
            if (0 === t.indexOf("private-encrypted-")) {
                if ("ReactNative" == navigator.product) {
                    var n = "Encrypted channels are not yet supported when using React Native builds.";
                    throw new s.UnsupportedFeature(n)
                }
                return o.default.createEncryptedChannel(t, e)
            }
            return 0 === t.indexOf("private-") ? o.default.createPrivateChannel(t, e) : 0 === t.indexOf("presence-") ? o.default.createPresenceChannel(t, e) : o.default.createChannel(t, e)
        }
        var i = n(9),
            o = n(43),
            s = n(31),
            a = function() {
                function t() {
                    this.channels = {}
                }
                return t.prototype.add = function(t, e) {
                    return this.channels[t] || (this.channels[t] = r(t, e)), this.channels[t]
                }, t.prototype.all = function() {
                    return i.values(this.channels)
                }, t.prototype.find = function(t) {
                    return this.channels[t]
                }, t.prototype.remove = function(t) {
                    var e = this.channels[t];
                    return delete this.channels[t], e
                }, t.prototype.disconnect = function() {
                    i.objectApply(this.channels, function(t) {
                        t.disconnect()
                    })
                }, t
            }();
        e.__esModule = !0, e.default = a
    }, function(t, e, n) {
        "use strict";

        function r(t, e) {
            return o.default.defer(function() {
                e(t)
            }), {
                abort: function() {},
                forceMinPriority: function() {}
            }
        }
        var i = n(43),
            o = n(11),
            s = n(31),
            a = n(9),
            u = function() {
                function t(t, e, n, r) {
                    this.name = t, this.priority = e, this.transport = n, this.options = r || {}
                }
                return t.prototype.isSupported = function() {
                    return this.transport.isSupported({
                        useTLS: this.options.useTLS
                    })
                }, t.prototype.connect = function(t, e) {
                    var n = this;
                    if (!this.isSupported()) return r(new s.UnsupportedStrategy, e);
                    if (this.priority < t) return r(new s.TransportPriorityTooLow, e);
                    var o = !1,
                        u = this.transport.createConnection(this.name, this.priority, this.options.key, this.options),
                        c = null,
                        h = function() {
                            u.unbind("initialized", h), u.connect()
                        },
                        f = function() {
                            c = i.default.createHandshake(u, function(t) {
                                o = !0, d(), e(null, t)
                            })
                        },
                        l = function(t) {
                            d(), e(t)
                        },
                        p = function() {
                            d();
                            var t;
                            t = a.safeJSONStringify(u), e(new s.TransportClosed(t))
                        },
                        d = function() {
                            u.unbind("initialized", h), u.unbind("open", f), u.unbind("error", l), u.unbind("closed", p)
                        };
                    return u.bind("initialized", h), u.bind("open", f), u.bind("error", l), u.bind("closed", p), u.initialize(), {
                        abort: function() {
                            o || (d(), c ? c.close() : u.close())
                        },
                        forceMinPriority: function(t) {
                            o || n.priority < t && (c ? c.close() : u.close())
                        }
                    }
                }, t
            }();
        e.__esModule = !0, e.default = u
    }, function(t, e, n) {
        "use strict";
        var r = n(9),
            i = n(11),
            o = n(12),
            s = function() {
                function t(t, e) {
                    this.strategies = t, this.loop = Boolean(e.loop), this.failFast = Boolean(e.failFast), this.timeout = e.timeout, this.timeoutLimit = e.timeoutLimit
                }
                return t.prototype.isSupported = function() {
                    return r.any(this.strategies, i.default.method("isSupported"))
                }, t.prototype.connect = function(t, e) {
                    var n = this,
                        r = this.strategies,
                        i = 0,
                        o = this.timeout,
                        s = null,
                        a = function(u, c) {
                            c ? e(null, c) : (i += 1, n.loop && (i %= r.length), i < r.length ? (o && (o *= 2, n.timeoutLimit && (o = Math.min(o, n.timeoutLimit))), s = n.tryStrategy(r[i], t, {
                                timeout: o,
                                failFast: n.failFast
                            }, a)) : e(!0))
                        };
                    return s = this.tryStrategy(r[i], t, {
                        timeout: o,
                        failFast: this.failFast
                    }, a), {
                        abort: function() {
                            s.abort()
                        },
                        forceMinPriority: function(e) {
                            t = e, s && s.forceMinPriority(e)
                        }
                    }
                }, t.prototype.tryStrategy = function(t, e, n, r) {
                    var i = null,
                        s = null;
                    return n.timeout > 0 && (i = new o.OneOffTimer(n.timeout, function() {
                        s.abort(), r(!0)
                    })), s = t.connect(e, function(t, e) {
                        t && i && i.isRunning() && !n.failFast || (i && i.ensureAborted(), r(t, e))
                    }), {
                        abort: function() {
                            i && i.ensureAborted(), s.abort()
                        },
                        forceMinPriority: function(t) {
                            s.forceMinPriority(t)
                        }
                    }
                }, t
            }();
        e.__esModule = !0, e.default = s
    }, function(t, e, n) {
        "use strict";

        function r(t, e, n) {
            var r = s.map(t, function(t, r, i, o) {
                return t.connect(e, n(r, o))
            });
            return {
                abort: function() {
                    s.apply(r, o)
                },
                forceMinPriority: function(t) {
                    s.apply(r, function(e) {
                        e.forceMinPriority(t)
                    })
                }
            }
        }

        function i(t) {
            return s.all(t, function(t) {
                return Boolean(t.error)
            })
        }

        function o(t) {
            t.error || t.aborted || (t.abort(), t.aborted = !0)
        }
        var s = n(9),
            a = n(11),
            u = function() {
                function t(t) {
                    this.strategies = t
                }
                return t.prototype.isSupported = function() {
                    return s.any(this.strategies, a.default.method("isSupported"))
                }, t.prototype.connect = function(t, e) {
                    return r(this.strategies, t, function(t, n) {
                        return function(r, o) {
                            return n[t].error = r, r ? void(i(n) && e(!0)) : (s.apply(n, function(t) {
                                t.forceMinPriority(o.transport.priority)
                            }), void e(null, o))
                        }
                    })
                }, t
            }();
        e.__esModule = !0, e.default = u
    }, function(t, e, n) {
        "use strict";

        function r(t) {
            return "pusherTransport" + (t ? "TLS" : "NonTLS")
        }

        function i(t) {
            var e = u.default.getLocalStorage();
            if (e) try {
                var n = e[r(t)];
                if (n) return JSON.parse(n)
            } catch (e) {
                s(t)
            }
            return null
        }

        function o(t, e, n) {
            var i = u.default.getLocalStorage();
            if (i) try {
                i[r(t)] = h.safeJSONStringify({

                    timestamp: a.default.now(),
                    transport: e,
                    latency: n
                })
            } catch (t) {}
        }

        function s(t) {
            var e = u.default.getLocalStorage();
            if (e) try {
                delete e[r(t)]
            } catch (t) {}
        }
        var a = n(11),
            u = n(2),
            c = n(65),
            h = n(9),
            f = function() {
                function t(t, e, n) {
                    this.strategy = t, this.transports = e, this.ttl = n.ttl || 18e5, this.usingTLS = n.useTLS, this.timeline = n.timeline
                }
                return t.prototype.isSupported = function() {
                    return this.strategy.isSupported()
                }, t.prototype.connect = function(t, e) {
                    var n = this.usingTLS,
                        r = i(n),
                        u = [this.strategy];
                    if (r && r.timestamp + this.ttl >= a.default.now()) {
                        var h = this.transports[r.transport];
                        h && (this.timeline.info({
                            cached: !0,
                            transport: r.transport,
                            latency: r.latency
                        }), u.push(new c.default([h], {
                            timeout: 2 * r.latency + 1e3,
                            failFast: !0
                        })))
                    }
                    var f = a.default.now(),
                        l = u.pop().connect(t, function r(i, c) {
                            i ? (s(n), u.length > 0 ? (f = a.default.now(), l = u.pop().connect(t, r)) : e(i)) : (o(n, c.transport.name, a.default.now() - f), e(null, c))
                        });
                    return {
                        abort: function() {
                            l.abort()
                        },
                        forceMinPriority: function(e) {
                            t = e, l && l.forceMinPriority(e)
                        }
                    }
                }, t
            }();
        e.__esModule = !0, e.default = f
    }, function(t, e, n) {
        "use strict";
        var r = n(12),
            i = function() {
                function t(t, e) {
                    var n = e.delay;
                    this.strategy = t, this.options = {
                        delay: n
                    }
                }
                return t.prototype.isSupported = function() {
                    return this.strategy.isSupported()
                }, t.prototype.connect = function(t, e) {
                    var n, i = this.strategy,
                        o = new r.OneOffTimer(this.options.delay, function() {
                            n = i.connect(t, e)
                        });
                    return {
                        abort: function() {
                            o.ensureAborted(), n && n.abort()
                        },
                        forceMinPriority: function(e) {
                            t = e, n && n.forceMinPriority(e)
                        }
                    }
                }, t
            }();
        e.__esModule = !0, e.default = i
    }, function(t, e) {
        "use strict";
        var n = function() {
            function t(t, e, n) {
                this.test = t, this.trueBranch = e, this.falseBranch = n
            }
            return t.prototype.isSupported = function() {
                var t = this.test() ? this.trueBranch : this.falseBranch;
                return t.isSupported()
            }, t.prototype.connect = function(t, e) {
                var n = this.test() ? this.trueBranch : this.falseBranch;
                return n.connect(t, e)
            }, t
        }();
        e.__esModule = !0, e.default = n
    }, function(t, e) {
        "use strict";
        var n = function() {
            function t(t) {
                this.strategy = t
            }
            return t.prototype.isSupported = function() {
                return this.strategy.isSupported()
            }, t.prototype.connect = function(t, e) {
                var n = this.strategy.connect(t, function(t, r) {
                    r && n.abort(), e(t, r)
                });
                return n
            }, t
        }();
        e.__esModule = !0, e.default = n
    }, function(t, e, n) {
        "use strict";
        var r = n(5);
        e.getGlobalConfig = function() {
            return {
                wsHost: r.default.host,
                wsPort: r.default.ws_port,
                wssPort: r.default.wss_port,
                wsPath: r.default.ws_path,
                httpHost: r.default.sockjs_host,
                httpPort: r.default.sockjs_http_port,
                httpsPort: r.default.sockjs_https_port,
                httpPath: r.default.sockjs_path,
                statsHost: r.default.stats_host,
                authEndpoint: r.default.channel_auth_endpoint,
                authTransport: r.default.channel_auth_transport,
                activity_timeout: r.default.activity_timeout,
                pong_timeout: r.default.pong_timeout,
                unavailable_timeout: r.default.unavailable_timeout
            }
        }, e.getClusterConfig = function(t) {
            return {
                wsHost: "ws-" + t + ".pusher.com",
                httpHost: "sockjs-" + t + ".pusher.com"
            }
        }
    }])
});