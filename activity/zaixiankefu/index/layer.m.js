/*! layer mobile-v1.5 弹层组件移动版 License LGPL http://sentsin.com/layui/layer/ By 贤心 */
;!function (a) {
    "use strict";
    var b = "";
    b = b ? b : document.scripts[document.scripts.length - 1].src.match(/[\s\S]*\//)[0];
    var c = document, d = "querySelectorAll", e = "getElementsByClassName", f = function (a) {
        return c[d](a)
    };
    document.head.appendChild(function () {
        var a = c.createElement("link");
        return a.href = b + "layer.css", a.type = "text/css", a.rel = "styleSheet", a.id = "layermcss", a
    }());
    var g = {type: 0, shade: !0, shadeClose: !0, fixed: !0, anim: !0};
    a.ready = {
        extend: function (a) {
            var b = JSON.parse(JSON.stringify(g));
            for (var c in a)b[c] = a[c];
            return b
        }, timer: {}, end: {}
    };
    var h = 0, i = ["layermbox"], j = function (a) {
        var b = this;
        b.config = ready.extend(a), b.view()
    };
    j.prototype.view = function () {
        var a = this, b = a.config, d = c.createElement("div");
        a.id = d.id = i[0] + h, d.setAttribute("class", i[0] + " " + i[0] + (b.type || 0)), d.setAttribute("index", h);
        var g = function () {
            var a = "object" == typeof b.title;
            return b.title ? '<h3 style="' + (a ? b.title[1] : "") + '">' + (a ? b.title[0] : b.title) + '</h3><button class="layermend"></button>' : ""
        }(), j = function () {
            var a, c = (b.btn || []).length;
            return 0 !== c && b.btn ? (a = '<span type="1">' + b.btn[0] + "</span>", 2 === c && (a = '<span type="0">' + b.btn[1] + "</span>" + a), '<div class="layermbtn">' + a + "</div>") : ""
        }();
        if (b.fixed || (b.top = b.hasOwnProperty("top") ? b.top : 100, b.style = b.style || "", b.style += " top:" + (c.body.scrollTop + b.top) + "px"), 2 === b.type && (b.content = '<i></i><i class="laymloadtwo"></i><i></i><div>' + (b.content || "") + "</div>"), d.innerHTML = (b.shade ? "<div " + ("string" == typeof b.shade ? 'style="' + b.shade + '"' : "") + ' class="laymshade"></div>' : "") + '<div class="layermmain" ' + (b.fixed ? "" : 'style="position:static;"') + '><div class="section"><div class="layermchild ' + (b.className ? b.className : "") + " " + (b.type || b.shade ? "" : "layermborder ") + (b.anim ? "layermanim" : "") + '" ' + (b.style ? 'style="' + b.style + '"' : "") + ">" + g + '<div class="layermcont">' + b.content + "</div>" + j + "</div></div></div>", !b.type || 2 === b.type) {
            var l = c[e](i[0] + b.type), m = l.length;
            m >= 1 && k.close(l[0].getAttribute("index"))
        }
        document.body.appendChild(d);
        var n = a.elem = f("#" + a.id)[0];
        setTimeout(function () {
            try {
                n.className = n.className + " layermshow"
            } catch (a) {
                return
            }
            b.success && b.success(n)
        }, 1), a.index = h++, a.action(b, n)
    }, j.prototype.action = function (a, b) {
        var c = this;
        if (a.time && (ready.timer[c.index] = setTimeout(function () {
                k.close(c.index)
            }, 1e3 * a.time)), a.title && (b[e]("layermend")[0].onclick = function () {
                a.cancel && a.cancel(), k.close(c.index)
            }), a.btn)for (var d = b[e]("layermbtn")[0].children, f = d.length, g = 0; f > g; g++)d[g].onclick = function () {
            var b = this.getAttribute("type");
            0 == b ? (a.no && a.no(), k.close(c.index)) : a.yes ? a.yes(c.index) : k.close(c.index)
        };
        if (a.shade && a.shadeClose) {
            var h = b[e]("laymshade")[0];
            h.onclick = function () {
                k.close(c.index, a.end)
            }, h.ontouchmove = function () {
                k.close(c.index, a.end)
            }
        }
        a.end && (ready.end[c.index] = a.end)
    };
    var k = {
        v: "1.5", index: h, open: function (a) {
            var b = new j(a || {});
            return b.index
        }, close: function (a) {
            var b = f("#" + i[0] + a)[0];
            b && (b.innerHTML = "", c.body.removeChild(b), clearTimeout(ready.timer[a]), delete ready.timer[a], "function" == typeof ready.end[a] && ready.end[a](), delete ready.end[a])
        }, closeAll: function () {
            for (var a = c[e](i[0]), b = 0, d = a.length; d > b; b++)k.close(0 | a[0].getAttribute("index"))
        }
    };
    "function" == typeof define ? define(function () {
        return k
    }) : a.layer = k
}(window);