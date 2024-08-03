(function (s, t, y, l, e) {
    y = [].concat(y);
    for (l = 0; l < y.length; l++) {
        e = s.createElement(t);
        e.rel = "stylesheet";
        e.href = y[l];
        s.getElementsByTagName("head")[0].appendChild(e);
    }
})(document, "link", ["../Resources/exibid_slide-styles.css"]);

(function (ex, h, i, b, e, o) {
    ex[e] = ex[e] || function () {
        (ex[e].a = ex[e].a || []).push(arguments);
    };
    b = [].concat(b);
    for (var l = 0; l < b.length; l++) {
        o = ex[e][b[l]] || h.createElement(i);
        o.async = 1;
        o.src = b[l];
        h.getElementsByTagName("head")[0].appendChild(o);
        ex[e][b[l]] = o;
    }
})(window, document, "script", ["../../Resources/slide12.js"], "xb_slide");
