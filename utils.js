Object.defineProperty(Object.prototype, "define", {
    configurable: true,
    enumerable: false,
    writable: true,
    value: function (name, value) {
        if (Object[name]) {
            delete Object[name];
        }
        Object.defineProperty(this, name, {
            configurable: true,
            enumerable: false,
            writable: true,
            value: value
        });
        return this;
    }
});
Object.prototype.define("map", function (mapFn) {
    let object = this;
    return Object.keys(object).reduce(function (result, key) {
        result[key] = mapFn(object[key]);
        return result;
    }, {});
});
Object.prototype.define("size", function () {
    return Object.keys(this).length;
});
Object.prototype.define("each", function (fn) {
    for (let k in this) {
        fn && fn.call(this, this[k], k);
    }
    return this;
});
Array.prototype.define("each", Array.prototype.forEach)
String.prototype.define('toTitleCase', function () {
    return this.replace(/\w\S*/g, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
});
String.prototype.define('escapeHTML', function () {
    let replacements = { "<": "&lt;", ">": "&gt;", "&": "&amp;", "'": "&apos;", "\"": "&quot;" };
    return this.replace(/[<>&'"]/g, function (character) {
        return replacements[character];
    });
});
Math.define("nativeRound", Math.round)
Math.define("round", function (i, n) {
    return +i.toFixed(n);
});

async function $get(url, data) {
    let result = false;
    try {
        result = await $.get(url, data);
    }
    catch (e) {
        console.error(e);
    }
    return result;
}
async function $getJSON(url, data) {
    let result = false;
    try {
        result = await $.getJSON(url, data);
    }
    catch (e) {
        console.error(e);
    }
    return result;
}

function getQueryString() {
    return Object.fromEntries(new URLSearchParams(location.search));
}
function query() {
    let qs = {}, slice = 1;
    let [page, name] = [...window.location.pathname.split("/").slice(slice)]
    qs.page = page, qs.name = name;
    return qs;
}
(function($) {
    $.fn.selected = function () {
        return $(this).find("option:selected").text();
    }
    $.fn.byValue = function (text) {
        return $(this).find("option").filter(function () {
            return $(this).text() === text;
        });
    }
    $.fn.selectOption = function (text) {
        return $(this).byValue(text).prop('selected', true);
    }
    $.fn.randomize = function (tree, childElem) {
        return this.each(function() {
            let $this = $(this);
            if (tree) $this = $(this).find(tree);
            
            let unsortedElems = $this.children(childElem);
            let elems = unsortedElems.clone();

            elems.sort(() => Math.round(Math.random()) - 0.5);
            for (let i = 0; i < elems.length; i++) {
                unsortedElems.eq(i).replaceWith(elems[i]);
            }
        });
    };
})(jQuery);
