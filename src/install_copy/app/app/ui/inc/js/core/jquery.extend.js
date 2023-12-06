/**
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
//------------------------------------------------
jQuery.fn.highlight = function (pat) {
    function innerHighlight(node, pat) {
        let skip = 0;
        if (node.nodeType === 3) {
            let pos = node.data.toUpperCase().indexOf(pat);
            if (pos >= 0) {
                let spanNode = document.createElement('span');
                spanNode.className = 'ui-highlight';
                let middleBit = node.splitText(pos);
                let middleClone = middleBit.cloneNode(true);
                spanNode.appendChild(middleClone);
                middleBit.parentNode.replaceChild(spanNode, middleBit);
                skip = 1;
            }
        } else if (node.nodeType === 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
            for (let i = 0; i < node.childNodes.length; ++i) {
                i += innerHighlight(node.childNodes[i], pat);
            }
        }
        return skip;
    }

    return this.each(function () {
        innerHighlight(this, pat.toUpperCase());
    });
};
jQuery.fn.removeHighlight = function () {
    function newNormalize(node) {
        for (let i = 0, children = node.childNodes, nodeCount = children.length; i < nodeCount; i++) {
            let child = children[i];
            if (child.nodeType === 1) {
                newNormalize(child);
                continue;
            }
            if (child.nodeType !== 3) {
                continue;
            }
            let next = child.nextSibling;
            if (next == null || next.nodeType !== 3) {
                continue;
            }
            let combined_text = child.nodeValue + next.nodeValue;
            let new_node = node.ownerDocument.createTextNode(combined_text);
            node.insertBefore(new_node, child);
            node.removeChild(child);
            node.removeChild(next);
            i--;
            nodeCount--;
        }
    }

    return this.find("span").each(function () {
        let thisParent = this.parentNode;
        thisParent.replaceChild(this.firstChild, this);
        newNormalize(thisParent);
    }).end();
};
//------------------------------------------------
$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            let keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode === '13') {
                ev.preventDefault();
                fnc.call(this, ev);
            }
        })
    })
};
//------------------------------------------------
$.fn.spaceKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '32') {
                ev.preventDefault();
                fnc.call(this, ev);
            }
        })
    })
};
//------------------------------------------------
$.fn.escapeKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '27') {
                ev.preventDefault();
                fnc.call(this, ev);
            }
        })
    })
};
//------------------------------------------------
$.fn.selectText = function(){
    this.find('input').each(function() {
        if($(this).prev().length == 0 || !$(this).prev().hasClass('p_copy')) {
            $('<p class="p_copy" style="position: absolute; z-index: -1;"></p>').insertBefore($(this));
        }
        $(this).prev().html($(this).val());
    });
    var doc = document;
    var element = this[0];
    console.log(this, element);
    if (doc.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        var selection = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};