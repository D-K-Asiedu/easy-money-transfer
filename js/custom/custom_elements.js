var SharedFunctions = {
    cpCss: (shadowRoot) => {
        const cp = document.createElement('link');
        cp.setAttribute('rel', 'stylesheet');
        cp.setAttribute('href', 'css/cp.css');
        shadowRoot.appendChild(cp);
    },
    cpOptionsCss: (shadowRoot) => {
        const cpOptions = document.createElement('link');
        cpOptions.setAttribute('rel', 'stylesheet');
        cpOptions.setAttribute('href', 'css/cp-options.css');
        shadowRoot.appendChild(cpOptions);
    },
    generalCss: (shadowRoot) => {
        // const leftNavCss = document.createElement('link');
        // leftNavCss.setAttribute('rel', 'stylesheet');
        // leftNavCss.setAttribute('href', 'css/leftnav.css');
        // shadowRoot.appendChild(leftNavCss);

        const bootstrapCss = document.createElement('link');
        bootstrapCss.setAttribute('rel', 'stylesheet');
        bootstrapCss.setAttribute('href', '../css/bootstrap.min.css');
        shadowRoot.appendChild(bootstrapCss);

        const mdbCss = document.createElement('link');
        mdbCss.setAttribute('rel', 'stylesheet');
        mdbCss.setAttribute('href', '../css/mdb.min.css');
        shadowRoot.appendChild(mdbCss);

        // const matCss = document.createElement('link');
        // matCss.setAttribute('rel', 'stylesheet');
        // matCss.setAttribute('href', '../css/materialize/materialize.min.css');
        // shadowRoot.appendChild(matCss);

        const fontAwesomeCss = document.createElement('link');
        fontAwesomeCss.setAttribute('rel', 'stylesheet');
        fontAwesomeCss.setAttribute('href', '../font/fontawesome/css/all.min.css');
        shadowRoot.appendChild(fontAwesomeCss);

        const styleCss = document.createElement('link');
        styleCss.setAttribute('rel', 'stylesheet');
        styleCss.setAttribute('href', '../css/style.min.css');
        shadowRoot.appendChild(styleCss);
    },
    vendorScripts: (shadowRoot) => {
        const jquery = document.createElement('script');
        jquery.src = "../js/jquery-3.3.1.min.js";
        shadowRoot.appendChild(jquery);

        const popper = document.createElement('script');
        popper.src = "../js/popper.min.js";
        shadowRoot.appendChild(popper);

        const bootstrap = document.createElement('script');
        bootstrap.src = "../js/bootstrap.min.js";
        shadowRoot.appendChild(bootstrap);

        const dtable = document.createElement('script');
        dtable.src = "../js/addons/datatables.min.js";
        shadowRoot.appendChild(dtable);

        const mdb = document.createElement('script');
        mdb.src = "../js/mdb.min.js";
        shadowRoot.appendChild(mdb);
    },
    customScripts: (shadowRoot) => {
        const ce_evt = document.createElement('script');
        ce_evt.src = "js/custom/custom_elem_evt.js";
        shadowRoot.appendChild(ce_evt);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    class ControlPanel extends HTMLElement {
        get open() {
            return this.hasAttribute('open');
        }
        set open(val) {
            if (val) {
                this.setAttribute('open', '');
            } else {
                this.removeAttribute('open');
            }
            this.toggleDrawer();
        }
        get disabled() {
            return this.hasAttribute('disabled');
        }
        set disabled(val) {
            if (val) {
                this.setAttribute('disabled', '');
            } else {
                this.removeAttribute('disabled');
            }
        }
        toggleDrawer() {

        }
        connectedCallback() {

        }
        disconnectedCallback() {

        }
        attributeChangedCallback(attrName, oldVal, newVal) {

        }
        get observedAttributes() {
            return ['disabled', 'open'];
        }
        constructor() {
            super();
            let template = document.getElementById('cp-template');
            let templateContent = template.content;
            const shadowRoot = this.attachShadow({ mode: 'open' });
            shadowRoot.appendChild(templateContent.cloneNode(true));
            SharedFunctions.generalCss(shadowRoot);
            SharedFunctions.cpCss(shadowRoot);

            this.addEventListener('click', e => {
                if (this.disabled) {
                    return;
                }
                this.toggleDrawer();
            });
        }
    }
    customElements.define('control-panel', ControlPanel);



    class ControlOptions extends HTMLElement {
        get navitems() {
            return this.hasAttribute('controls');
        }
        get items() {
            return this.getAttribute("controls");
        }
        connectedCallback() {

        }
        setClass(elemClass, elem) {
            if (elemClass != null || elemClass != undefined) {
                elem.setAttribute("class", elemClass);
            }
        }
        setId(elemId, elem) {
            if (elemId != null || elemId != undefined) {
                elem.setAttribute("id", elemId);
            }
        }
        setIcon(icon, elem) {
            if (icon != null || icon != undefined) {
                let icn = document.createElement('i');
                icn.setAttribute('class', icon);
                elem.appendChild(icn);
            }
        }
        setAnchor(innerText, elem, anchorParams) {
            let a = document.createElement('a');
            if (anchorParams != null || anchorParams != undefined) {
                if (anchorParams.class != null || anchorParams.class != undefined) {
                    a.setAttribute('class', anchorParams.class);
                }
                if (anchorParams.id != null || anchorParams.id != undefined) {
                    a.setAttribute('id', anchorParams.id);
                }
                if (anchorParams.spaRoute != null || anchorParams.spaRoute != undefined) {
                    let spaRouteName = anchorParams.spaRoute.name;
                    if (spaRouteName != null || spaRouteName != undefined) {
                        logRoute(spaRouteName);
                        a.setAttribute('href', "#" + spaRouteName);
                        if (anchorParams.spaRoute.default) {
                            //setting default route
                            location.hash = "#" + spaRouteName;
                            if (anchorParams.spaRoute.baseURL != null || anchorParams.spaRoute.baseURL != undefined) {
                                checkAndChangeRoute(anchorParams.spaRoute.baseURL);
                            } else {
                                alert("Base URL was not set");
                            }
                        }
                    }
                }
                if (anchorParams.modal != null || anchorParams.modal != undefined) {
                    $(document).ready(() => {
                        $(a).on('click', () => {
                            $("#" + anchorParams.modal.id).modal("show");
                        });
                    });
                }
                if (anchorParams.evt != null || anchorParams.evt != undefined) {
                    $(document).ready(() => {
                        $(a).on('click', () => {
                            let eventFunction = eval(anchorParams.evt);
                            eventFunction();
                        });
                    });
                }
            }
            a.textContent = innerText;
            elem.appendChild(a);
        }

        setDetailSummary(summaryText, params) {
            let summary = document.createElement('summary');
            let details = document.createElement('details');
            details.appendChild(summary);
            summary.textContent = summaryText;
            return details;
        }

        setStyle(style, elem) {
            if (!style) {
                elem.setAttribute("style", style);
            }
        }
        getMenu(items, parentUl) {
            for (let i = 0; i < items.length; i++) {
                let li = document.createElement('li');
                let name = items[i].name;
                let elemId = items[i].id;
                let elemClass = items[i].class;
                let ricon = items[i].ricon;
                let licon = items[i].licon;
                let style = items[i].style;
                let anchorParams = items[i].anchor;
                let liEvent = items[i].evt;
                let html = items[i].html;
                let accordion = items[i].accordion;
                let details = null;

                if (liEvent != null || liEvent != undefined) {
                    $(document).ready(() => {
                        $(li).on('click', () => {
                            let eventFunction = eval(liEvent);
                            eventFunction();
                        });
                    });
                }

                if (accordion != null || accordion != undefined) {
                    if (accordion.show) {
                        details = this.setDetailSummary(accordion.summary, {});
                    }
                }

                if (html != null || html != undefined) {
                    $(document).ready(() => {
                        $(li).html(html.body);
                    });
                }

                if (licon != null || licon != undefined) {
                    this.setIcon(licon.class, li);
                }
                if (name != null || name != undefined) {
                    this.setAnchor(name, li, anchorParams);
                }
                if (ricon != null || ricon != undefined) {
                    this.setIcon(ricon.class, li);
                }
                if (style != null || style != undefined) {
                    this.setStyle(style, li);
                }
                this.setId(elemId, li);
                this.setClass(elemClass, li);
                parentUl.appendChild(li);
                let submenu = items[i].submenu;
                if (submenu != null || submenu != undefined) {
                    if (submenu.length != 0) {
                        let ul = document.createElement('ul');
                        ul.setAttribute('class', 'control-ul');
                        if (details != null) {
                            details.appendChild(ul);
                            li.appendChild(details);
                        } else {
                            li.appendChild(ul);
                        }
                        this.getMenu(submenu, ul);
                    }
                }
            }
        }
        constructor() {
            super();
            const shadowRoot = this.attachShadow({ mode: 'open' });
            if (this.navitems) {
                SharedFunctions.generalCss(shadowRoot);
                SharedFunctions.cpOptionsCss(shadowRoot);
                let items = JSON.parse(this.items);
                let parentUl = document.createElement('ul');
                parentUl.setAttribute('class', 'control-ul parent-ul');
                this.getMenu(items, parentUl);
                shadowRoot.appendChild(parentUl);
                SharedFunctions.vendorScripts(shadowRoot);
                SharedFunctions.customScripts(shadowRoot);
            } else {
                console.log('control-option has not been defined yet');
            }
        }
    }
    customElements.define('control-options', ControlOptions);
});