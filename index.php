<?php
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$parts = parse_url($url);
$path = $parts['path'];
$keys = explode('/', $path);

// var_dump($keys);

parse_str($parts['query'], $qu);
?>

<!DOCTYPE html>
<html lang="en-US" prefix="og: https://ogp.me/ns#">

<head>
  <meta charset="UTF-8">
  <meta name="theme-color" content="#A0D8B3">
  <script>
    if (navigator.userAgent.match(/MSIE|Internet Explorer/i) || navigator.userAgent.match(/Trident\/7\..*?rv:11/i)) {
      var href = document.location.href;
      if (!href.match(/[?&]nowprocket/)) {
        if (href.indexOf("?") == -1) {
          if (href.indexOf("#") == -1) {
            document.location.href = href + "?nowprocket=1"
          } else {
            document.location.href = href.replace("#", "?nowprocket=1#")
          }
        } else {
          if (href.indexOf("#") == -1) {
            document.location.href = href + "&nowprocket=1"
          } else {
            document.location.href = href.replace("#", "&nowprocket=1#")
          }
        }
      }
    }
  </script>
  <script>
    class RocketLazyLoadScripts {
      constructor(e) {
        this.triggerEvents = e, this.eventOptions = {
          passive: !0
        }, this.userEventListener = this.triggerListener.bind(this), this.delayedScripts = {
          normal: [],
          async: [],
          defer: []
        }, this.allJQueries = []
      }
      _addUserInteractionListener(e) {
        this.triggerEvents.forEach((t => window.addEventListener(t, e.userEventListener, e.eventOptions)))
      }
      _removeUserInteractionListener(e) {
        this.triggerEvents.forEach((t => window.removeEventListener(t, e.userEventListener, e.eventOptions)))
      }
      triggerListener() {
        this._removeUserInteractionListener(this), "loading" === document.readyState ? document.addEventListener(
          "DOMContentLoaded", this._loadEverythingNow.bind(this)) : this._loadEverythingNow()
      }
      async _loadEverythingNow() {
        this._delayEventListeners(), this._delayJQueryReady(this), this._handleDocumentWrite(), this
          ._registerAllDelayedScripts(), this._preloadAllScripts(), await this._loadScriptsFromList(this
            .delayedScripts.normal), await this._loadScriptsFromList(this.delayedScripts.defer), await this
          ._loadScriptsFromList(this.delayedScripts.async), await this._triggerDOMContentLoaded(), await this
          ._triggerWindowLoad(), window.dispatchEvent(new Event("rocket-allScriptsLoaded"))
      }
      _registerAllDelayedScripts() {
        document.querySelectorAll("script[type=rocketlazyloadscript]").forEach((e => {
          e.hasAttribute("src") ? e.hasAttribute("async") && !1 !== e.async ? this.delayedScripts.async.push(
              e) : e.hasAttribute("defer") && !1 !== e.defer || "module" === e.getAttribute(
              "data-rocket-type") ?
            this.delayedScripts.defer.push(e) : this.delayedScripts.normal.push(e) : this.delayedScripts.normal
            .push(e)
        }))
      }
      async _transformScript(e) {
        return await this._requestAnimFrame(), new Promise((t => {
          const n = document.createElement("script");
          let r;
          [...e.attributes].forEach((e => {
            let t = e.nodeName;
            "type" !== t && ("data-rocket-type" === t && (t = "type", r = e.nodeValue), n.setAttribute(t,
              e.nodeValue))
          })), e.hasAttribute("src") ? (n.addEventListener("load", t), n.addEventListener("error", t)) : (n
            .text = e.text, t()), e.parentNode.replaceChild(n, e)
        }))
      }
      async _loadScriptsFromList(e) {
        const t = e.shift();
        return t ? (await this._transformScript(t), this._loadScriptsFromList(e)) : Promise.resolve()
      }
      _preloadAllScripts() {
        var e = document.createDocumentFragment();
        [...this.delayedScripts.normal, ...this.delayedScripts.defer, ...this.delayedScripts.async].forEach((t => {
          const n = t.getAttribute("src");
          if (n) {
            const t = document.createElement("link");
            t.href = n, t.rel = "preload", t.as = "script", e.appendChild(t)
          }
        })), document.head.appendChild(e)
      }
      _delayEventListeners() {
        let e = {};

        function t(t, n) {
          ! function(t) {
            function n(n) {
              return e[t].eventsToRewrite.indexOf(n) >= 0 ? "rocket-" + n : n
            }
            e[t] || (e[t] = {
              originalFunctions: {
                add: t.addEventListener,
                remove: t.removeEventListener
              },
              eventsToRewrite: []
            }, t.addEventListener = function() {
              arguments[0] = n(arguments[0]), e[t].originalFunctions.add.apply(t, arguments)
            }, t.removeEventListener = function() {
              arguments[0] = n(arguments[0]), e[t].originalFunctions.remove.apply(t, arguments)
            })
          }(t), e[t].eventsToRewrite.push(n)
        }

        function n(e, t) {
          let n = e[t];
          Object.defineProperty(e, t, {
            get: () => n || function() {},
            set(r) {
              e["rocket" + t] = n = r
            }
          })
        }
        t(document, "DOMContentLoaded"), t(window, "DOMContentLoaded"), t(window, "load"), t(window, "pageshow"), t(
          document, "readystatechange"), n(document, "onreadystatechange"), n(window, "onload"), n(window,
          "onpageshow")
      }
      _delayJQueryReady(e) {
        let t = window.jQuery;
        Object.defineProperty(window, "jQuery", {
          get: () => t,
          set(n) {
            if (n && n.fn && !e.allJQueries.includes(n)) {
              n.fn.ready = n.fn.init.prototype.ready = function(t) {
                e.domReadyFired ? t.bind(document)(n) : document.addEventListener("rocket-DOMContentLoaded", (
                  () => t.bind(document)(n)))
              };
              const t = n.fn.on;
              n.fn.on = n.fn.init.prototype.on = function() {
                if (this[0] === window) {
                  function e(e) {
                    return e.split(" ").map((e => "load" === e || 0 === e.indexOf("load.") ?
                      "rocket-jquery-load" : e)).join(" ")
                  }
                  "string" == typeof arguments[0] || arguments[0] instanceof String ? arguments[0] = e(
                    arguments[0]) : "object" == typeof arguments[0] && Object.keys(arguments[0]).forEach((
                    t => {
                      delete Object.assign(arguments[0], {
                        [e(t)]: arguments[0][t]
                      })[t]
                    }))
                }
                return t.apply(this, arguments), this
              }, e.allJQueries.push(n)
            }
            t = n
          }
        })
      }
      async _triggerDOMContentLoaded() {
        this.domReadyFired = !0, await this._requestAnimFrame(), document.dispatchEvent(new Event(
            "rocket-DOMContentLoaded")), await this._requestAnimFrame(), window.dispatchEvent(new Event(
            "rocket-DOMContentLoaded")), await this._requestAnimFrame(), document.dispatchEvent(new Event(
            "rocket-readystatechange")), await this._requestAnimFrame(), document.rocketonreadystatechange && document
          .rocketonreadystatechange()
      }
      async _triggerWindowLoad() {
        await this._requestAnimFrame(), window.dispatchEvent(new Event("rocket-load")), await this
          ._requestAnimFrame(), window.rocketonload && window.rocketonload(), await this._requestAnimFrame(), this
          .allJQueries.forEach((e => e(window).trigger("rocket-jquery-load"))), window.dispatchEvent(new Event(
            "rocket-pageshow")), await this._requestAnimFrame(), window.rocketonpageshow && window.rocketonpageshow()
      }
      _handleDocumentWrite() {
        const e = new Map;
        document.write = document.writeln = function(t) {
          const n = document.currentScript,
            r = document.createRange(),
            i = n.parentElement;
          let o = e.get(n);
          void 0 === o && (o = n.nextSibling, e.set(n, o));
          const a = document.createDocumentFragment();
          r.setStart(a, 0), a.appendChild(r.createContextualFragment(t)), i.insertBefore(a, o)
        }
      }
      async _requestAnimFrame() {
        return new Promise((e => requestAnimationFrame(e)))
      }
      static run() {
        const e = new RocketLazyLoadScripts(["keydown", "mousemove", "touchmove", "touchstart", "touchend", "wheel"]);
        e._addUserInteractionListener(e)
      }
    }
    RocketLazyLoadScripts.run();
  </script>
  <style type="text/css">
    .wdp-comment-text img {

      max-width: 100% !important;

    }
  </style>
  <script type="rocketlazyloadscript">window._wca = window._wca || [];</script>

  <title>Khairil & Riska</title>
  <style id="rocket-critical-css">
    h1,
    h2 {
      overflow-wrap: break-word
    }

    ul {
      overflow-wrap: break-word
    }

    :root {
      --wp--preset--font-size--normal: 16px;
      --wp--preset--font-size--huge: 42px
    }

    html {
      line-height: 1.15;
      -webkit-text-size-adjust: 100%
    }

    *,
    :after,
    :before {
      -webkit-box-sizing: border-box;
      box-sizing: border-box
    }

    body {
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #333;
      background-color: #fff;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale
    }

    h1,
    h2 {
      margin-top: .5rem;
      margin-bottom: 1rem;
      font-family: inherit;
      font-weight: 500;
      line-height: 1.2;
      color: inherit
    }

    h1 {
      font-size: 2.5rem
    }

    h2 {
      font-size: 2rem
    }

    a {
      background-color: transparent;
      text-decoration: none;
      color: #c36
    }

    sup {
      font-size: 75%;
      line-height: 0;
      position: relative;
      vertical-align: baseline
    }

    sup {
      top: -.5em
    }

    img {
      border-style: none;
      height: auto;
      max-width: 100%
    }

    label {
      display: inline-block;
      line-height: 1;
      vertical-align: middle
    }

    input,
    select {
      font-family: inherit;
      font-size: 1rem;
      line-height: 1.5;
      margin: 0
    }

    input[type=text],
    select {
      width: 100%;
      border: 1px solid #666;
      border-radius: 3px;
      padding: .5rem 1rem
    }

    input {
      overflow: visible
    }

    select {
      text-transform: none
    }

    [type=button] {
      width: auto;
      -webkit-appearance: button
    }

    [type=button]::-moz-focus-inner {
      border-style: none;
      padding: 0
    }

    [type=button]:-moz-focusring {
      outline: 1px dotted ButtonText
    }

    [type=button] {
      display: inline-block;
      font-weight: 400;
      color: #c36;
      text-align: center;
      white-space: nowrap;
      background-color: transparent;
      border: 1px solid #c36;
      padding: .5rem 1rem;
      font-size: 1rem;
      border-radius: 3px
    }

    ::-webkit-file-upload-button {
      -webkit-appearance: button;
      font: inherit
    }

    select {
      display: block
    }

    ul {
      margin-top: 0;
      margin-bottom: 0;
      border: 0;
      outline: 0;
      font-size: 100%;
      vertical-align: baseline;
      background: transparent
    }

    .site-navigation {
      grid-area: nav-menu;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      -webkit-box-flex: 1;
      -ms-flex-positive: 1;
      flex-grow: 1
    }

    .elementor-kit-1016 {
      --e-global-color-primary: #6EC1E4;
      --e-global-color-secondary: #54595F;
      --e-global-color-text: #7A7A7A;
      --e-global-color-accent: #61CE70;
      --e-global-typography-primary-font-family: "Roboto";
      --e-global-typography-primary-font-weight: 600;
      --e-global-typography-secondary-font-family: "Roboto Slab";
      --e-global-typography-secondary-font-weight: 400;
      --e-global-typography-text-font-family: "Roboto";
      --e-global-typography-text-font-weight: 400;
      --e-global-typography-accent-font-family: "Roboto";
      --e-global-typography-accent-font-weight: 500
    }

    .elementor-section.elementor-section-boxed>.elementor-container {
      max-width: 1140px
    }

    .elementor-widget:not(:last-child) {
      margin-bottom: 20px
    }

    @media (max-width:1024px) {
      .elementor-section.elementor-section-boxed>.elementor-container {
        max-width: 1024px
      }
    }

    @media (max-width:767px) {
      .elementor-section.elementor-section-boxed>.elementor-container {
        max-width: 767px
      }
    }

    .elementor-widget-heading .elementor-heading-title {
      font-family: var(--e-global-typography-primary-font-family), Sans-serif;
      font-weight: var(--e-global-typography-primary-font-weight)
    }

    .elementor-widget-button .elementor-button {
      font-family: var(--e-global-typography-accent-font-family), Sans-serif;
      font-weight: var(--e-global-typography-accent-font-weight)
    }

    .elementor-widget-form .elementor-field-group>label {
      font-family: var(--e-global-typography-text-font-family), Sans-serif;
      font-weight: var(--e-global-typography-text-font-weight)
    }

    .elementor-31860 .elementor-element.elementor-element-679373c>.elementor-container {
      max-width: 360px;
      min-height: 100vh
    }

    .elementor-31860 .elementor-element.elementor-element-679373c>.elementor-container>.elementor-column>.elementor-widget-wrap {
      align-content: center;
      align-items: center
    }

    .elementor-31860 .elementor-element.elementor-element-679373c:not(.elementor-motion-effects-element-type-background) {
      background-color: #17164F
    }

    .elementor-31860 .elementor-element.elementor-element-679373c>.elementor-background-overlay {
      opacity: 0.35
    }

    .elementor-31860 .elementor-element.elementor-element-679373c {
      z-index: 999
    }

    .elementor-31860 .elementor-element.elementor-element-7b9eab5>.elementor-widget-wrap>.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute) {
      margin-bottom: 5px
    }

    .elementor-31860 .elementor-element.elementor-element-a146c17 img {
      width: 100%;
      max-width: 100%
    }

    .elementor-31860 .elementor-element.elementor-element-a146c17 .dce-animations {
      animation-play-state: running;
      -webkit-animation-play-state: running;
      animation-name: pulsa;
      -webkit-animation-name: pulsa;
      transform-origin: center center;
      -webkit-transform-origin: center center;
      animation-iteration-count: infinite;
      -webkit-animation-iteration-count: infinite;
      animation-duration: 5.3s;
      -webkit-animation-duration: 5.3s;
      animation-delay: 0s;
      -webkit-animation-delay: 0s;
      animation-timing-function: ease-in-out;
      -webkit-animation-timing-function: ease-in-out;
      animation-direction: alternate-reverse;
      -webkit-animation-direction: alternate-reverse;
      animation-fill-mode: both;
      -webkit-animation-fill-mode: both
    }

    .elementor-31860 .elementor-element.elementor-element-49ece72 {
      text-align: center
    }

    .elementor-31860 .elementor-element.elementor-element-49ece72 .elementor-heading-title {
      color: #EEC373;
      font-family: "Prata", Sans-serif;
      font-size: 30px;
      font-weight: 200;
      text-transform: capitalize
    }

    .elementor-31860 .elementor-element.elementor-element-49ece72>.elementor-widget-container {
      margin: 0px 0px 10px 0px
    }

    .elementor-31860 .elementor-element.elementor-element-0fd6b72 {
      text-align: center
    }

    .elementor-31860 .elementor-element.elementor-element-0fd6b72 .elementor-heading-title {
      color: #F4DFBA;
      font-family: "Montserrat", Sans-serif;
      font-size: 16px;
      font-weight: 500;
      text-transform: none;
      line-height: 20px;
      letter-spacing: 0px
    }

    .elementor-31860 .elementor-element.elementor-element-0fd6b72>.elementor-widget-container {
      margin: 0px 0px 0px 0px
    }

    .elementor-31860 .elementor-element.elementor-element-aa4c2f0 {
      text-align: center
    }

    .elementor-31860 .elementor-element.elementor-element-aa4c2f0 .elementor-heading-title {
      color: #F4DFBA;
      font-family: "Montserrat", Sans-serif;
      font-size: 18px;
      font-weight: 300;
      text-transform: none;
      line-height: 20px;
      letter-spacing: 0px
    }

    .elementor-31860 .elementor-element.elementor-element-aa4c2f0>.elementor-widget-container {
      margin: 0px 0px 0px 0px
    }

    .elementor-31860 .elementor-element.elementor-element-aba8b03 .elementor-button .elementor-align-icon-left {
      margin-right: 10px
    }

    .elementor-31860 .elementor-element.elementor-element-aba8b03 .elementor-button {
      font-family: "Elsie", Sans-serif;
      font-weight: 400;
      letter-spacing: 0px;
      fill: #232323;
      color: #232323;
      background-color: #EEC373;
      border-radius: 35px 35px 35px 35px
    }

    .elementor-31860 .elementor-element.elementor-element-3b34a4e .elementor-field-group>label {
      color: #7F6000
    }

    .elementor-31860 .elementor-element.elementor-element-3b34a4e .elementor-field-group>label {
      font-family: "Roboto", Sans-serif;
      font-size: 18px;
      font-weight: 500
    }

    .elementor-31860 .elementor-element.elementor-element-4ab6e8b:not(.elementor-motion-effects-element-type-background) {
      background-color: #FFFFFF;
      background-image: url("https://i1.wp.com/einvite.id/wp-content/uploads/golden-2715-01.webp");
      background-position: bottom center;
      background-repeat: no-repeat;
      background-size: cover
    }

    .elementor-31860 .elementor-element.elementor-element-4ab6e8b>.elementor-background-overlay {
      opacity: 0.5
    }

    .elementor-31860 .elementor-element.elementor-element-4ab6e8b {
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
      padding: 5px 0px 0px 0px
    }

    .elementor-31860 .elementor-element.elementor-element-ec6d50b>.elementor-element-populated {
      margin: 0px 380px 0px 380px;
      --e-column-margin-right: 380px;
      --e-column-margin-left: 380px
    }

    .elementor-31860 .elementor-element.elementor-element-e11ab96 {
      padding: 0px 0px 0px 0px
    }

    .elementor-31860 .elementor-element.elementor-element-c13645d.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
      align-content: center;
      align-items: center
    }

    .elementor-31860 .elementor-element.elementor-element-c13645d:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap {
      background-color: #FFFFFF
    }

    .elementor-31860 .elementor-element.elementor-element-c13645d>.elementor-element-populated {
      border-style: solid;
      border-color: #D5A43B;
      box-shadow: 0px 0px 10px 0px #D5A43B;
      margin: 5px 5px 5px 5px;
      --e-column-margin-right: 5px;
      --e-column-margin-left: 5px;
      padding: 5px 5px 5px 5px
    }

    .elementor-31860 .elementor-element.elementor-element-c13645d>.elementor-element-populated {
      border-radius: 12px 12px 12px 12px
    }

    .elementor-31860 .elementor-element.elementor-element-a1e2bf0 img {
      max-width: 80%
    }

    .elementor-31860 .elementor-element.elementor-element-f5ce532.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
      align-content: center;
      align-items: center
    }

    .elementor-31860 .elementor-element.elementor-element-f5ce532:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap {
      background-color: #FFFFFF
    }

    .elementor-31860 .elementor-element.elementor-element-f5ce532>.elementor-element-populated {
      border-style: solid;
      border-color: #D5A43B;
      box-shadow: 0px 0px 10px 0px #D5A43B;
      margin: 5px 5px 5px 5px;
      --e-column-margin-right: 5px;
      --e-column-margin-left: 5px;
      padding: 5px 5px 5px 5px
    }

    .elementor-31860 .elementor-element.elementor-element-f5ce532>.elementor-element-populated {
      border-radius: 12px 12px 12px 12px
    }

    .elementor-31860 .elementor-element.elementor-element-24bcc6e img {
      max-width: 80%
    }

    .elementor-31860 .elementor-element.elementor-element-c600222.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
      align-content: center;
      align-items: center
    }

    .elementor-31860 .elementor-element.elementor-element-c600222:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap {
      background-color: #FFFFFF
    }

    .elementor-31860 .elementor-element.elementor-element-c600222>.elementor-element-populated {
      border-style: solid;
      border-color: #D5A43B;
      box-shadow: 0px 0px 10px 0px #D5A43B;
      margin: 5px 5px 5px 5px;
      --e-column-margin-right: 5px;
      --e-column-margin-left: 5px;
      padding: 5px 5px 5px 5px
    }

    .elementor-31860 .elementor-element.elementor-element-c600222>.elementor-element-populated {
      border-radius: 12px 12px 12px 12px
    }

    .elementor-31860 .elementor-element.elementor-element-caa5a65 img {
      max-width: 80%
    }

    .elementor-31860 .elementor-element.elementor-element-e3c09dd.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
      align-content: center;
      align-items: center
    }

    .elementor-31860 .elementor-element.elementor-element-e3c09dd:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap {
      background-color: #FFFFFF
    }

    .elementor-31860 .elementor-element.elementor-element-e3c09dd>.elementor-element-populated {
      border-style: solid;
      border-color: #D5A43B;
      box-shadow: 0px 0px 10px 0px #D5A43B;
      margin: 5px 5px 5px 5px;
      --e-column-margin-right: 5px;
      --e-column-margin-left: 5px;
      padding: 5px 5px 5px 5px
    }

    .elementor-31860 .elementor-element.elementor-element-e3c09dd>.elementor-element-populated {
      border-radius: 12px 12px 12px 12px
    }

    .elementor-31860 .elementor-element.elementor-element-fd1d91a img {
      max-width: 80%
    }

    .elementor-31860 .elementor-element.elementor-element-fd1d91a>.elementor-widget-container {
      padding: 5px 0px 0px 0px
    }

    .elementor-31860 .elementor-element.elementor-element-5c92d31.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
      align-content: center;
      align-items: center
    }

    .elementor-31860 .elementor-element.elementor-element-5c92d31:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap {
      background-color: #FFFFFF
    }

    .elementor-31860 .elementor-element.elementor-element-5c92d31>.elementor-element-populated {
      border-style: solid;
      border-color: #D5A43B;
      box-shadow: 0px 0px 10px 0px #D5A43B;
      margin: 5px 5px 5px 5px;
      --e-column-margin-right: 5px;
      --e-column-margin-left: 5px;
      padding: 5px 5px 5px 5px
    }

    .elementor-31860 .elementor-element.elementor-element-5c92d31>.elementor-element-populated {
      border-radius: 12px 12px 12px 12px
    }

    .elementor-31860 .elementor-element.elementor-element-cefff53 img {
      max-width: 80%
    }

    .elementor-31860 .elementor-element.elementor-element-89c2ad3 .elementor-icon-wrapper {
      text-align: right
    }

    .elementor-31860 .elementor-element.elementor-element-89c2ad3.elementor-view-default .elementor-icon {
      color: #8E7561;
      border-color: #8E7561
    }

    .elementor-31860 .elementor-element.elementor-element-89c2ad3 .elementor-icon {
      font-size: 37px
    }

    .elementor-31860 .elementor-element.elementor-element-89c2ad3 .elementor-icon i {
      transform: rotate(0deg)
    }

    .elementor-31860 .elementor-element.elementor-element-89c2ad3>.elementor-widget-container {
      margin: 0px 0px 0px 0px
    }

    .elementor-31860 .elementor-element.elementor-element-89c2ad3 {
      width: auto;
      max-width: auto;
      bottom: 9%
    }

    body:not(.rtl) .elementor-31860 .elementor-element.elementor-element-89c2ad3 {
      left: 97%
    }

    @media (max-width:1024px) {
      .elementor-31860 .elementor-element.elementor-element-49ece72 .elementor-heading-title {
        font-size: 25px
      }

      .elementor-31860 .elementor-element.elementor-element-4ab6e8b {
        padding: 0% 20% 0% 20%
      }

      .elementor-31860 .elementor-element.elementor-element-ec6d50b>.elementor-element-populated {
        margin: 0px 0px 0px 0px;
        --e-column-margin-right: 0px;
        --e-column-margin-left: 0px;
        padding: 0px 0px 0px 0px
      }

      .elementor-31860 .elementor-element.elementor-element-89c2ad3>.elementor-widget-container {
        margin: 0px 0px 0px 0px
      }

      .elementor-31860 .elementor-element.elementor-element-89c2ad3 {
        width: auto;
        max-width: auto;
        bottom: 10%
      }

      body:not(.rtl) .elementor-31860 .elementor-element.elementor-element-89c2ad3 {
        left: 94%
      }
    }

    @media (max-width:767px) {
      .elementor-31860 .elementor-element.elementor-element-679373c {
        z-index: 999
      }

      .elementor-31860 .elementor-element.elementor-element-7b9eab5.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
        align-content: center;
        align-items: center
      }

      .elementor-31860 .elementor-element.elementor-element-7b9eab5.elementor-column>.elementor-widget-wrap {
        justify-content: center
      }

      .elementor-31860 .elementor-element.elementor-element-49ece72 .elementor-heading-title {
        font-size: 25px;
        line-height: 1.2em
      }

      .elementor-31860 .elementor-element.elementor-element-49ece72>.elementor-widget-container {
        margin: 10px 0px 10px 0px
      }

      .elementor-31860 .elementor-element.elementor-element-0fd6b72 .elementor-heading-title {
        font-size: 18px
      }

      .elementor-31860 .elementor-element.elementor-element-0fd6b72>.elementor-widget-container {
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px
      }

      .elementor-31860 .elementor-element.elementor-element-aa4c2f0 .elementor-heading-title {
        font-size: 18px
      }

      .elementor-31860 .elementor-element.elementor-element-aa4c2f0>.elementor-widget-container {
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px
      }

      .elementor-31860 .elementor-element.elementor-element-4ab6e8b {
        margin-top: 0px;
        margin-bottom: 0px;
        padding: 0% 0% 0% 0%
      }

      .elementor-31860 .elementor-element.elementor-element-ec6d50b>.elementor-element-populated {
        margin: 0px 0px 0px 0px;
        --e-column-margin-right: 0px;
        --e-column-margin-left: 0px;
        padding: 0px 0px 0px 0px
      }

      .elementor-31860 .elementor-element.elementor-element-e11ab96 {
        padding: 0% 10% 0% 10%
      }

      .elementor-31860 .elementor-element.elementor-element-c13645d {
        width: 20%
      }

      .elementor-31860 .elementor-element.elementor-element-f5ce532 {
        width: 20%
      }

      .elementor-31860 .elementor-element.elementor-element-c600222 {
        width: 20%
      }

      .elementor-31860 .elementor-element.elementor-element-e3c09dd {
        width: 20%
      }

      .elementor-31860 .elementor-element.elementor-element-5c92d31 {
        width: 20%
      }

      .elementor-31860 .elementor-element.elementor-element-89c2ad3 .elementor-icon {
        font-size: 27px
      }

      .elementor-31860 .elementor-element.elementor-element-89c2ad3>.elementor-widget-container {
        margin: 0px 0px 0px 0px
      }

      .elementor-31860 .elementor-element.elementor-element-89c2ad3 {
        width: auto;
        max-width: auto;
        bottom: 8%
      }

      body:not(.rtl) .elementor-31860 .elementor-element.elementor-element-89c2ad3 {
        left: 91%
      }
    }

    @media (min-width:1025px) {
      .elementor-31860 .elementor-element.elementor-element-4ab6e8b:not(.elementor-motion-effects-element-type-background) {
        background-attachment: scroll
      }
    }

    :root {
      --swiper-theme-color: #007aff
    }

    :root {
      --swiper-navigation-size: 44px
    }

    :root {
      --jp-carousel-primary-color: #fff;
      --jp-carousel-primary-subtle-color: #999;
      --jp-carousel-bg-color: #000;
      --jp-carousel-bg-faded-color: #222;
      --jp-carousel-border-color: #3a3a3a
    }
  </style>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCrimson%20Pro%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CGreat%20Vibes%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCroissant%20One%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMontserrat%20Alternates%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CEB%20Garamond%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Condensed%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CElsie%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CAbril%20Fatface%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CSacramento%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCrimson%20Pro%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CGreat%20Vibes%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCroissant%20One%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMontserrat%20Alternates%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CEB%20Garamond%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Condensed%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CElsie%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CAbril%20Fatface%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CSacramento%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;display=swap" media="print" onload="this.media='all'" /><noscript>
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCrimson%20Pro%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CGreat%20Vibes%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCroissant%20One%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMontserrat%20Alternates%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CEB%20Garamond%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Condensed%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CElsie%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CAbril%20Fatface%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CSacramento%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;display=swap" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" />
  </noscript>
  <meta name="description" content="Tema Undangan Online Premium Green White Gold Roses untuk undangan online anda" />
  <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large" />
  <link rel="canonical" href="https://sandevs.com" />
  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="Khairil Dan Riska - By Rey Sandevs" />
  <meta property="og:description" content="Tema Undangan Online Premium By Rey Sandevs " />
  <meta property="og:url" content="https://sandevs.com />
  <meta property=" og:site_name" content="sandevs.com" />
  <meta property="article:section" content="Premium" />
  <meta property="og:updated_time" content="2023-04-01T12:54:44+08:00" />
  <meta property="og:image" content="gambar/Cover-Photo.webp" />
  <meta property="og:image:secure_url" content="gambar/Cover-Photo.webp" />
  <meta property="og:image:width" content="600" />
  <meta property="og:image:height" content="600" />
  <meta property="og:image:alt" content="undangan online premium" />
  <meta property="og:image:type" content="image/jpeg" />
  <meta property="og:video" content="" />
  <meta property="video:duration" content="19" />
  <meta property="ya:ovs:upload_date" content="2022-02-18" />
  <meta property="ya:ovs:allow_embed" content="true" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Khairil Dan Riska - By Rey Sandevs" />
  <meta name="twitter:description" content="Tema Undangan Online Premium By Rey Sandevs" />
  <meta name="twitter:image" content="gambar/Cover-Photo.webp" />
  <meta name="twitter:label1" content="Written by" />
  <meta name="twitter:data1" content="Rey Riyan Sanjaya" />
  <meta name="twitter:label2" content="Time to read" />
  <meta name="twitter:data2" content="1 minute" />
  <!-- <script type="application/ld+json" class="rank-math-schema-pro">
    {
      "@context": "https://schema.org",
      "@graph": [{
        "@type": "Place",
        "@id": "https://einvite.id/#place",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Jl. Teras Bukit, Jimbaran, Kita Selatan",
          "addressLocality": "Badung",
          "addressRegion": "Bali",
          "postalCode": "80361",
          "addressCountry": "ID"
        }
      }, {
        "@type": "Organization",
        "@id": "https://einvite.id/#organization",
        "name": "einvite.id - Jasa Undangan Online Website Digital",
        "url": "https://einvite.id",
        "email": "einvite888@gmail.com",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Jl. Teras Bukit, Jimbaran, Kita Selatan",
          "addressLocality": "Badung",
          "addressRegion": "Bali",
          "postalCode": "80361",
          "addressCountry": "ID"
        },
        "logo": {
          "@type": "ImageObject",
          "@id": "https://einvite.id/#logo",
          "url": "https://einvite.id/wp-content/uploads/2021/10/logoeinvite2021.png",
          "contentUrl": "https://einvite.id/wp-content/uploads/2021/10/logoeinvite2021.png",
          "caption": "einvite.id - Jasa Undangan Online Website Digital",
          "inLanguage": "en-US",
          "width": "220",
          "height": "54"
        },
        "contactPoint": [{
          "@type": "ContactPoint",
          "telephone": "+6288907029988",
          "contactType": "customer support"
        }],
        "location": {
          "@id": "https://einvite.id/#place"
        }
      }, {
        "@type": "WebSite",
        "@id": "https://einvite.id/#website",
        "url": "https://einvite.id",
        "name": "einvite.id - Jasa Undangan Online Website Digital",
        "publisher": {
          "@id": "https://einvite.id/#organization"
        },
        "inLanguage": "en-US"
      }, {
        "@type": "ImageObject",
        "@id": "https://i0.wp.com/einvite.id/wp-content/uploads/PP17-Green-White-Gold-Roses.jpg?fit=600%2C600&amp;ssl=1",
        "url": "https://i0.wp.com/einvite.id/wp-content/uploads/PP17-Green-White-Gold-Roses.jpg?fit=600%2C600&amp;ssl=1",
        "width": "600",
        "height": "600",
        "inLanguage": "en-US"
      }, {
        "@type": "WebPage",
        "@id": "https://einvite.id/premium-17/#webpage",
        "url": "https://einvite.id/premium-17/",
        "name": "Premium 17 - Green White Gold Roses - einvite.id",
        "datePublished": "2022-09-08T09:54:20+08:00",
        "dateModified": "2023-04-01T12:54:44+08:00",
        "isPartOf": {
          "@id": "https://einvite.id/#website"
        },
        "primaryImageOfPage": {
          "@id": "https://i0.wp.com/einvite.id/wp-content/uploads/PP17-Green-White-Gold-Roses.jpg?fit=600%2C600&amp;ssl=1"
        },
        "inLanguage": "en-US"
      }, {
        "@type": "VideoObject",
        "name": "PREWEDDING IRWAN  &amp; DINDA",
        "description": "Tema Undangan Online Premium Green White Gold Roses untuk undangan online anda",
        "uploadDate": "2022-02-18",
        "thumbnailUrl": "https://einvite.id/wp-content/uploads/maxresdefault-1.jpg",
        "embedUrl": "https://www.youtube.com/embed/XH4bt8rgLxU",
        "duration": "PT0M19S",
        "width": "1280",
        "height": "720",
        "isFamilyFriendly": "True",
        "@id": "https://einvite.id/premium-17/#schema-734958",
        "isPartOf": {
          "@id": "https://einvite.id/premium-17/#webpage"
        },
        "publisher": {
          "@id": "https://einvite.id/#organization"
        },
        "inLanguage": "en-US",
        "mainEntityOfPage": {
          "@id": "https://einvite.id/premium-17/#webpage"
        }
      }]
    }
  </script> -->

  <link rel="dns-prefetch" href="//stats.wp.com" />
  <link rel="dns-prefetch" href="//fonts.gstatic.com" />
  <link rel="dns-prefetch" href="//i0.wp.com" />
  <link rel="dns-prefetch" href="//i1.wp.com" />
  <link rel="dns-prefetch" href="//i2.wp.com" />
  <link rel="dns-prefetch" href="//c0.wp.com" />
  <link rel="dns-prefetch" href="//www.google-analytics.com" />
  <link rel="dns-prefetch" href="//fonts.googleapis.com" />
  <link rel="dns-prefetch" href="//pixel.wp.com" />
  <link rel="dns-prefetch" href="//i3.wp.com" />
  <link href="https://fonts.gstatic.com" crossorigin rel="preconnect" />
  <link href="//i0.wp.com" rel="preconnect" />
  <link href="//i1.wp.com" rel="preconnect" />
  <link href="//i2.wp.com" rel="preconnect" />
  <link href="//i3.wp.com" rel="preconnect" />
  <style>
    img.wp-smiley,
    img.emoji {
      display: inline !important;
      border: none !important;
      box-shadow: none !important;
      height: 1em !important;
      width: 1em !important;
      margin: 0 0.07em !important;
      vertical-align: -0.1em !important;
      background: none !important;
      padding: 0 !important;
    }
  </style>
  <link data-minify="1" rel="preload" href="css/bdt-uikit.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/ep-helper.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/mediaelementplayer-legacy.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/wp-mediaelement.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/wc-blocks-vendors-style.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/p/woocommerce/7.5.1/packages/woocommerce-blocks/build/wc-blocks-style.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />

  <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.3/dist/index.bundle.min.js"></script>

  <style id="global-styles-inline-css">
    body {
      --wp--preset--color--black: #000000;
      --wp--preset--color--cyan-bluish-gray: #abb8c3;
      --wp--preset--color--white: #ffffff;
      --wp--preset--color--pale-pink: #f78da7;
      --wp--preset--color--vivid-red: #cf2e2e;
      --wp--preset--color--luminous-vivid-orange: #ff6900;
      --wp--preset--color--luminous-vivid-amber: #fcb900;
      --wp--preset--color--light-green-cyan: #7bdcb5;
      --wp--preset--color--vivid-green-cyan: #00d084;
      --wp--preset--color--pale-cyan-blue: #8ed1fc;
      --wp--preset--color--vivid-cyan-blue: #0693e3;
      --wp--preset--color--vivid-purple: #9b51e0;
      --wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg, rgba(6, 147, 227, 1) 0%, rgb(155, 81, 224) 100%);
      --wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg, rgb(122, 220, 180) 0%, rgb(0, 208, 130) 100%);
      --wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg, rgba(252, 185, 0, 1) 0%, rgba(255, 105, 0, 1) 100%);
      --wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg, rgba(255, 105, 0, 1) 0%, rgb(207, 46, 46) 100%);
      --wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg, rgb(238, 238, 238) 0%, rgb(169, 184, 195) 100%);
      --wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg, rgb(74, 234, 220) 0%, rgb(151, 120, 209) 20%, rgb(207, 42, 186) 40%, rgb(238, 44, 130) 60%, rgb(251, 105, 98) 80%, rgb(254, 248, 76) 100%);
      --wp--preset--gradient--blush-light-purple: linear-gradient(135deg, rgb(255, 206, 236) 0%, rgb(152, 150, 240) 100%);
      --wp--preset--gradient--blush-bordeaux: linear-gradient(135deg, rgb(254, 205, 165) 0%, rgb(254, 45, 45) 50%, rgb(107, 0, 62) 100%);
      --wp--preset--gradient--luminous-dusk: linear-gradient(135deg, rgb(255, 203, 112) 0%, rgb(199, 81, 192) 50%, rgb(65, 88, 208) 100%);
      --wp--preset--gradient--pale-ocean: linear-gradient(135deg, rgb(255, 245, 203) 0%, rgb(182, 227, 212) 50%, rgb(51, 167, 181) 100%);
      --wp--preset--gradient--electric-grass: linear-gradient(135deg, rgb(202, 248, 128) 0%, rgb(113, 206, 126) 100%);
      --wp--preset--gradient--midnight: linear-gradient(135deg, rgb(2, 3, 129) 0%, rgb(40, 116, 252) 100%);
      --wp--preset--duotone--dark-grayscale: url('#wp-duotone-dark-grayscale');
      --wp--preset--duotone--grayscale: url('#wp-duotone-grayscale');
      --wp--preset--duotone--purple-yellow: url('#wp-duotone-purple-yellow');
      --wp--preset--duotone--blue-red: url('#wp-duotone-blue-red');
      --wp--preset--duotone--midnight: url('#wp-duotone-midnight');
      --wp--preset--duotone--magenta-yellow: url('#wp-duotone-magenta-yellow');
      --wp--preset--duotone--purple-green: url('#wp-duotone-purple-green');
      --wp--preset--duotone--blue-orange: url('#wp-duotone-blue-orange');
      --wp--preset--font-size--small: 13px;
      --wp--preset--font-size--medium: 20px;
      --wp--preset--font-size--large: 36px;
      --wp--preset--font-size--x-large: 42px;
    }

    .has-black-color {
      color: var(--wp--preset--color--black) !important;
    }

    .has-cyan-bluish-gray-color {
      color: var(--wp--preset--color--cyan-bluish-gray) !important;
    }

    .has-white-color {
      color: var(--wp--preset--color--white) !important;
    }

    .has-pale-pink-color {
      color: var(--wp--preset--color--pale-pink) !important;
    }

    .has-vivid-red-color {
      color: var(--wp--preset--color--vivid-red) !important;
    }

    .has-luminous-vivid-orange-color {
      color: var(--wp--preset--color--luminous-vivid-orange) !important;
    }

    .has-luminous-vivid-amber-color {
      color: var(--wp--preset--color--luminous-vivid-amber) !important;
    }

    .has-light-green-cyan-color {
      color: var(--wp--preset--color--light-green-cyan) !important;
    }

    .has-vivid-green-cyan-color {
      color: var(--wp--preset--color--vivid-green-cyan) !important;
    }

    .has-pale-cyan-blue-color {
      color: var(--wp--preset--color--pale-cyan-blue) !important;
    }

    .has-vivid-cyan-blue-color {
      color: var(--wp--preset--color--vivid-cyan-blue) !important;
    }

    .has-vivid-purple-color {
      color: var(--wp--preset--color--vivid-purple) !important;
    }

    .has-black-background-color {
      background-color: var(--wp--preset--color--black) !important;
    }

    .has-cyan-bluish-gray-background-color {
      background-color: var(--wp--preset--color--cyan-bluish-gray) !important;
    }

    .has-white-background-color {
      background-color: var(--wp--preset--color--white) !important;
    }

    .has-pale-pink-background-color {
      background-color: var(--wp--preset--color--pale-pink) !important;
    }

    .has-vivid-red-background-color {
      background-color: var(--wp--preset--color--vivid-red) !important;
    }

    .has-luminous-vivid-orange-background-color {
      background-color: var(--wp--preset--color--luminous-vivid-orange) !important;
    }

    .has-luminous-vivid-amber-background-color {
      background-color: var(--wp--preset--color--luminous-vivid-amber) !important;
    }

    .has-light-green-cyan-background-color {
      background-color: var(--wp--preset--color--light-green-cyan) !important;
    }

    .has-vivid-green-cyan-background-color {
      background-color: var(--wp--preset--color--vivid-green-cyan) !important;
    }

    .has-pale-cyan-blue-background-color {
      background-color: var(--wp--preset--color--pale-cyan-blue) !important;
    }

    .has-vivid-cyan-blue-background-color {
      background-color: var(--wp--preset--color--vivid-cyan-blue) !important;
    }

    .has-vivid-purple-background-color {
      background-color: var(--wp--preset--color--vivid-purple) !important;
    }

    .has-black-border-color {
      border-color: var(--wp--preset--color--black) !important;
    }

    .has-cyan-bluish-gray-border-color {
      border-color: var(--wp--preset--color--cyan-bluish-gray) !important;
    }

    .has-white-border-color {
      border-color: var(--wp--preset--color--white) !important;
    }

    .has-pale-pink-border-color {
      border-color: var(--wp--preset--color--pale-pink) !important;
    }

    .has-vivid-red-border-color {
      border-color: var(--wp--preset--color--vivid-red) !important;
    }

    .has-luminous-vivid-orange-border-color {
      border-color: var(--wp--preset--color--luminous-vivid-orange) !important;
    }

    .has-luminous-vivid-amber-border-color {
      border-color: var(--wp--preset--color--luminous-vivid-amber) !important;
    }

    .has-light-green-cyan-border-color {
      border-color: var(--wp--preset--color--light-green-cyan) !important;
    }

    .has-vivid-green-cyan-border-color {
      border-color: var(--wp--preset--color--vivid-green-cyan) !important;
    }

    .has-pale-cyan-blue-border-color {
      border-color: var(--wp--preset--color--pale-cyan-blue) !important;
    }

    .has-vivid-cyan-blue-border-color {
      border-color: var(--wp--preset--color--vivid-cyan-blue) !important;
    }

    .has-vivid-purple-border-color {
      border-color: var(--wp--preset--color--vivid-purple) !important;
    }

    .has-vivid-cyan-blue-to-vivid-purple-gradient-background {
      background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;
    }

    .has-light-green-cyan-to-vivid-green-cyan-gradient-background {
      background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;
    }

    .has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background {
      background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;
    }

    .has-luminous-vivid-orange-to-vivid-red-gradient-background {
      background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;
    }

    .has-very-light-gray-to-cyan-bluish-gray-gradient-background {
      background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;
    }

    .has-cool-to-warm-spectrum-gradient-background {
      background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;
    }

    .has-blush-light-purple-gradient-background {
      background: var(--wp--preset--gradient--blush-light-purple) !important;
    }

    .has-blush-bordeaux-gradient-background {
      background: var(--wp--preset--gradient--blush-bordeaux) !important;
    }

    .has-luminous-dusk-gradient-background {
      background: var(--wp--preset--gradient--luminous-dusk) !important;
    }

    .has-pale-ocean-gradient-background {
      background: var(--wp--preset--gradient--pale-ocean) !important;
    }

    .has-electric-grass-gradient-background {
      background: var(--wp--preset--gradient--electric-grass) !important;
    }

    .has-midnight-gradient-background {
      background: var(--wp--preset--gradient--midnight) !important;
    }

    .has-small-font-size {
      font-size: var(--wp--preset--font-size--small) !important;
    }

    .has-medium-font-size {
      font-size: var(--wp--preset--font-size--medium) !important;
    }

    .has-large-font-size {
      font-size: var(--wp--preset--font-size--large) !important;
    }

    .has-x-large-font-size {
      font-size: var(--wp--preset--font-size--x-large) !important;
    }
  </style>
  <link rel="preload" href="css/frontend.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="https://einvite.id/wp-content/plugins/edge-cache-html-cloudflare-workers/public/css/cloudflare-edge-cache-public.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/extension.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="https://einvite.id/wp-content/plugins/piotnet-addons-for-elementor-pro/assets/css/minify/woocommerce-sales-funnels.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="https://einvite.id/wp-content/plugins/piotnet-addons-for-elementor/assets/css/minify/extension.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <style id="woocommerce-inline-inline-css">
    .woocommerce form .form-row .required {
      visibility: visible;
    }
  </style>
  <link rel="preload" href="css/forms.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/wdp-centered-timeline.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/wdp-horizontal-styles.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/wp-content/plugins/weddingpress/assets/css/wdp-fontello.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/exad-styles.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/wp-content/plugins/weddingpress/assets/css/cr.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/wdp_style.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" />
  <style id="wdp_style-inline-css">
    .wdp-wrapper {

      font-size: 14px
    }



    .wdp-wrapper ul.wdp-container-comments li.wdp-item-comment .wdp-comment-avatar img {

      max-width: 28px;

      max-height: 28px;

    }

    .wdp-wrapper ul.wdp-container-comments li.wdp-item-comment .wdp-comment-content {

      margin-left: 38px;

    }

    .wdp-wrapper ul.wdp-container-comments li.wdp-item-comment ul .wdp-comment-avatar img {

      max-width: 24px;

      max-height: 24px;

    }

    .wdp-wrapper ul.wdp-container-comments li.wdp-item-comment ul ul .wdp-comment-avatar img {

      max-width: 21px;

      max-height: 21px;

    }
  </style>
  <link rel="preload" href="https://einvite.id/wp-content/themes/hello-elementor/style.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="https://einvite.id/wp-content/themes/hello-elementor/theme.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/elementor-icons.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/frontend4.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/post-1016.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/frontend2.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/wp-content/uploads/element-pack/minified/css/ep-styles.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/style.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/css/dashicons.min.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/frontend3.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/wdp.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/guest-book.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/all.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/v4-shims.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/wp-content/uploads/elementor/css/global.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/post-64448.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/wp-content/uploads/elementor/css/post-22737.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/general.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/fontawesome.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/brands.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/solid.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="css/regular.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/p/jetpack/11.9.1/css/jetpack.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <script id="jquery-core-js-extra">
    var pp = {
      "ajax_url": "https:\/\/einvite.id\/wp-admin\/admin-ajax.php"
    };
  </script>
  <script src="https://c0.wp.com/c/6.0.3/wp-includes/js/jquery/jquery.min.js" id="jquery-core-js"></script>
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/js/jquery/jquery-migrate.min.js?ver=1683821879" id="jquery-migrate-js"></script>
  <script data-minify="1" src="js/cloudflare-edge-cache-public.js" id="cloudflare-edge-cache-js"></script>
  <script src="js/minify extension.min.js" id="pafe-extension-js"></script>
  <script src="js/woocommerce-sales-funnels.min.js" id="pafe-woocommerce-sales-funnels-script-js"></script>
  <script src="js/extension.min.js" id="pafe-extension-free-js"></script>
  <script defer src="https://stats.wp.com/s-202319.js" id="woocommerce-analytics-js"></script>
  <script src="js/v4-shims.min.js" id="font-awesome-4-shim-js"></script>
  <link rel="https://api.w.org/" href="https://einvite.id/wp-json/" />
  <link rel="alternate" type="application/json" href="https://einvite.id/wp-json/wp/v2/posts/64448" />
  <link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://einvite.id/xmlrpc.php?rsd" />
  <link rel="wlwmanifest" type="application/wlwmanifest+xml" href="https://einvite.id/wp-includes/wlwmanifest.xml" />
  <meta name="generator" content="WordPress 6.0.3" />
  <link rel="shortlink" href="https://einvite.id/?p=64448" />
  <!-- <link rel="alternate" type="application/json+oembed"
    href="https://einvite.id/wp-json/oembed/1.0/embed?url=https%3A%2F%2Feinvite.id%2Fpremium-17%2F" /> -->
  <!-- <link rel="alternate" type="text/xml+oembed"
    href="https://einvite.id/wp-json/oembed/1.0/embed?url=https%3A%2F%2Feinvite.id%2Fpremium-17%2F&#038;format=xml" /> -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script type="rocketlazyloadscript" data-rocket-type="text/javascript">
    var AFFWP = AFFWP || {};
		AFFWP.referral_var = 'ref';
		AFFWP.expiration = 30;
		AFFWP.debug = 0;


		AFFWP.referral_credit_last = 1;
		</script>

  <script type="rocketlazyloadscript">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TCPP7JP');</script>


  <meta name="mobile-web-app-capable" content="yes">

  <meta name="apudi-verification" content="8aab29caa31f599024b617acf189d640">

  <meta name="google" content="notranslate" />

  <meta name="format-detection" content="telephone=no">

  <meta name="color-scheme" content="light dark">
  <style>
    .frosted {
      border-radius: 10px;
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
    }

    .frosted2 {
      background: rgba(62, 146, 232, 0.43);
      border-radius: 10px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(11.4px);
      -webkit-backdrop-filter: blur(11.4px);
      border: 1px solid rgba(62, 146, 232, 0.69);
    }

    .frosted3 {
      background: rgba(255, 255, 255, 0.22);
      border-radius: 10px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(12.6px);
      -webkit-backdrop-filter: blur(12.6px);
      border: 1px solid rgba(255, 255, 255, 0.7);
    }

    .frosted4 {
      /* From https://css.glass */
      background: rgba(255, 255, 255, 0.36);
      border-radius: 10px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(8.5px);
      -webkit-backdrop-filter: blur(8.5px);
    }
  </style>
  <style>
    /*Hide konfirmasi wdp comment*/
    .wdp-wrapper.wdp-golden .wdp-wrap-form .wdp-container-form select.wdp-select {
      display: none
    }

    /*bullet and numbering link*/
    ul {
      display: block;
      list-style-type: disc;
      margin-top: 1em;
      margin-bottom: 1 em;
      margin-left: 0;
      margin-right: 0;
      padding-left: 20px
    }

    ol {
      display: block;
      list-style-type: decimal;
      margin-top: 1em;
      margin-bottom: 1em;
      margin-left: 2em;
      margin-right: 0;
      padding-left: 20px
    }

    li {
      display: list-item
    }

    dl {
      display: block;
      margin-top: 1em;
      margin-bottom: 1em;
      margin-left: 0;
      margin-right: 0
    }

    dt {
      display: block
    }

    dd {
      display: block;
      margin-left: 20px
    }

    .elementor-widget-container p {
      margin-bottom: 10px
    }

    a {
      color: #c36
    }

    /*CSS Heading*/
    h1 {
      display: block;
      font-size: 1.5em;
      margin: .67em 0;
      font-weight: 600
    }

    h2 {
      display: block;
      font-size: 1.2em;
      margin: .83em 0;
      font-weight: 600
    }

    h3 {
      display: block;
      font-size: 1em;
      margin: 1em 0;
      font-weight: 500
    }

    h4 {
      display: block;
      font-size: .91em;
      margin: 1.33em 0;
      font-weight: 500
    }

    h5 {
      display: block;
      font-size: .83em;
      margin: 1.67em 0;
      font-weight: 500
    }

    h6 {
      display: block;
      font-size: .67em;
      margin: 2.33em 0;
      font-weight: 500
    }

    /*titik dua pada addon undangan cart*/
    .woocommerce td.product-name dl.variation dt {
      display: none;
    }

    /*Antrean*/
    .antrean {
      color: #C5A880;
      font-family: "Poppins", Sans-serif;
      font-size: 16px;
      font-weight: 500;
      line-height: 16px;
    }


    /*css lainnya di bawah*/
  </style>
  <style>
    .goyang-1 img {
      animation-name: goyang1;
      animation-duration: 12s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
    }

    .goyang-12 img {
      animation-name: goyang1;
      animation-duration: 12s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
      transform-origin: top right;
    }

    .goyang-2 img {
      animation-name: goyang1;
      animation-duration: 9s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
    }

    .goyang-22 img {
      animation-name: goyang1;
      animation-duration: 9s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
      transform-origin: top right;
    }

    .goyang-3 img {
      animation-name: goyang1;
      animation-duration: 7s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
    }

    .goyang-32 img {
      animation-name: goyang1;
      animation-duration: 7s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
      transform-origin: top right;
    }

    .goyang-4 img {
      animation-name: goyang4;
      animation-duration: 9s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
    }

    .goyang-42 img {
      animation-name: goyang4;
      animation-duration: 9s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
      transform-origin: top right;
    }

    .zoom-1 {
      transform: scale(1);
      animation: zoom 5s infinite;
    }

    .zoom-2 img {
      transform: scale(1);
      animation: zoom 5s infinite;
    }

    .naik-turun {
      animation-name: naikturun;
      animation-duration: 5s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
    }

    .naik-turun2 {
      animation-name: naikturun;
      animation-duration: 6s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
    }

    .putar {
      animation: rotate 20s linear infinite;
    }

    @-webkit-keyframes rotate {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes rotate {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes goyang1 {
      0% {
        transform: rotate(0deg) scale(1);
      }

      50% {
        transform: rotate(9deg) scale(1.18);
      }

      100% {
        transform: rotate(0deg) scale(1);
      }
    }

    @keyframes goyang2 {
      0% {
        transform: rotate(0deg) scale(1);
      }

      25% {
        transform: rotate(-4deg) scale(1.05);
      }

      50% {
        transform: rotate(-8deg) scale(1.1);
      }

      75% {
        transform: rotate(-4deg) scale(1.05);
      }

      100% {
        transform: rotate(0deg) scale(1);
      }
    }

    @keyframes goyang3 {
      0% {
        transform: rotate(0deg) scale(1);
      }

      25% {
        transform: rotate(-4deg) scale(1.1);
      }

      50% {
        transform: rotate(0deg) scale(1);
      }

      75% {
        transform: rotate(4deg) scale(1.1);
      }

      100% {
        transform: rotate(0deg) scale(1);
      }
    }

    @keyframes goyang4 {
      0% {
        transform: rotate(0deg);
      }

      25% {
        transform: rotate(-4deg);
      }

      75% {
        transform: rotate(4deg);
      }

      100% {
        transform: rotate(0deg);
      }
    }

    @keyframes zoom {
      0% {
        transform: scale(0.95);
      }

      60% {
        transform: scale(1);
      }

      100% {
        transform: scale(0.95);
      }
    }

    @keyframes naikturun {
      0% {
        transform: translate(0px, 0px);
      }

      50% {
        transform: translate(0px, -10px);
      }

      100% {
        transform: translate(0px, 0px);
      }
    }

    @keyframes naikturun2 {
      0% {
        transform: translate(0px, 0px);
      }

      50% {
        transform: translate(0px, -10px);
      }

      100% {
        transform: translate(0px, 0px);
      }
    }
  </style>
  <style>
    img#wpstats {
      display: none
    }
  </style>
  <link rel="preload" as="font" href="fa/eicons.woff2" crossorigin>
  <link rel="preload" as="font" href="fa/fa-brands-400.woff" crossorigin>
  <link rel="preload" as="font" href="fa/fa-regular-400.woff2" crossorigin>
  <link rel="preload" as="font" href="fa/fa-solid-900.woff2" crossorigin>
  <link rel="preload" as="font" href="fa/Calibri.woff2" crossorigin>

  <script type="text/javascript">
    window.onload = maxWindow;

    function maxWindow() {
        window.moveTo(0, 0);

        if (document.all) {
            top.window.resizeTo(screen.availWidth, screen.availHeight);
        }

        else if (document.layers || document.getElementById) {
            if (top.window.outerHeight < screen.availHeight || top.window.outerWidth < screen.availWidth) {
                top.window.outerHeight = screen.availHeight;
                top.window.outerWidth = screen.availWidth;
            }
        }
    }
</script> 
  <noscript>
    <style>
      .woocommerce-product-gallery {
        opacity: 1 !important;
      }
    </style>
  </noscript>
  <link rel="icon" href="https://i0.wp.com/einvite.id/wp-content/uploads/einvite-fav-2023.png?fit=32%2C30&#038;ssl=1" sizes="32x32" />
  <link rel="icon" href="https://i0.wp.com/einvite.id/wp-content/uploads/einvite-fav-2023.png?fit=120%2C111&#038;ssl=1" sizes="192x192" />
  <link rel="apple-touch-icon" href="https://i0.wp.com/einvite.id/wp-content/uploads/einvite-fav-2023.png?fit=120%2C111&#038;ssl=1" />
  <meta name="msapplication-TileImage" content="https://i0.wp.com/einvite.id/wp-content/uploads/einvite-fav-2023.png?fit=120%2C111&#038;ssl=1" />

  <style>
    @media (max-width:767px) {
      .pafe-sticky-header-fixed-start-on-mobile {
        position: fixed !important;
        top: 0;
        width: 100%;
        z-index: 99;
      }
    }

    @media (min-width:768px) and (max-width:1024px) {
      .pafe-sticky-header-fixed-start-on-tablet {
        position: fixed !important;
        top: 0;
        width: 100%;
        z-index: 99;
      }
    }

    @media (min-width:1025px) {
      .pafe-sticky-header-fixed-start-on-desktop {
        position: fixed !important;
        top: 0;
        width: 100%;
        z-index: 99;
      }
    }
  </style>
  <style>
    .pswp.pafe-lightbox-modal {
      display: none;
    }
  </style>
  <script type="rocketlazyloadscript">
    /*! loadCSS rel=preload polyfill. [c]2017 Filament Group, Inc. MIT License */
(function(w){"use strict";if(!w.loadCSS){w.loadCSS=function(){}}
var rp=loadCSS.relpreload={};rp.support=(function(){var ret;try{ret=w.document.createElement("link").relList.supports("preload")}catch(e){ret=!1}
return function(){return ret}})();rp.bindMediaToggle=function(link){var finalMedia=link.media||"all";function enableStylesheet(){link.media=finalMedia}
if(link.addEventListener){link.addEventListener("load",enableStylesheet)}else if(link.attachEvent){link.attachEvent("onload",enableStylesheet)}
setTimeout(function(){link.rel="stylesheet";link.media="only x"});setTimeout(enableStylesheet,3000)};rp.poly=function(){if(rp.support()){return}
var links=w.document.getElementsByTagName("link");for(var i=0;i<links.length;i++){var link=links[i];if(link.rel==="preload"&&link.getAttribute("as")==="style"&&!link.getAttribute("data-loadcss")){link.setAttribute("data-loadcss",!0);rp.bindMediaToggle(link)}}};if(!rp.support()){rp.poly();var run=w.setInterval(rp.poly,500);if(w.addEventListener){w.addEventListener("load",function(){rp.poly();w.clearInterval(run)})}else if(w.attachEvent){w.attachEvent("onload",function(){rp.poly();w.clearInterval(run)})}}
if(typeof exports!=="undefined"){exports.loadCSS=loadCSS}
else{w.loadCSS=loadCSS}}(typeof global!=="undefined"?global:this))
</script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
</head>

<body class="post-template post-template-elementor_canvas single single-post postid-64448 single-format-standard wp-custom-logo theme-hello-elementor woocommerce-no-js elementor-default elementor-template-canvas elementor-kit-1016 elementor-page elementor-page-64448 elementor-page-1790" onload="toggleFullscreen();">
  <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;">
    <defs>
      <filter id="wp-duotone-dark-grayscale">
        <fecolormatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 " />
        <fecomponenttransfer color-interpolation-filters="sRGB">
          <fefuncr type="table" tablevalues="0 0.49803921568627" />
          <fefuncg type="table" tablevalues="0 0.49803921568627" />
          <fefuncb type="table" tablevalues="0 0.49803921568627" />
          <fefunca type="table" tablevalues="1 1" />
        </feComponentTransfer>
        <fecomposite in2="SourceGraphic" operator="in" />
      </filter>
    </defs>
  </svg><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;">
    <defs>
      <filter id="wp-duotone-grayscale">
        <fecolormatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 " />
        <fecomponenttransfer color-interpolation-filters="sRGB">
          <fefuncr type="table" tablevalues="0 1" />
          <fefuncg type="table" tablevalues="0 1" />
          <fefuncb type="table" tablevalues="0 1" />
          <fefunca type="table" tablevalues="1 1" />
        </feComponentTransfer>
        <fecomposite in2="SourceGraphic" operator="in" />
      </filter>
    </defs>
  </svg><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;">
    <defs>
      <filter id="wp-duotone-purple-yellow">
        <fecolormatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 " />
        <fecomponenttransfer color-interpolation-filters="sRGB">
          <fefuncr type="table" tablevalues="0.54901960784314 0.98823529411765" />
          <fefuncg type="table" tablevalues="0 1" />
          <fefuncb type="table" tablevalues="0.71764705882353 0.25490196078431" />
          <fefunca type="table" tablevalues="1 1" />
        </feComponentTransfer>
        <fecomposite in2="SourceGraphic" operator="in" />
      </filter>
    </defs>
  </svg><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;">
    <defs>
      <filter id="wp-duotone-blue-red">
        <fecolormatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 " />
        <fecomponenttransfer color-interpolation-filters="sRGB">
          <fefuncr type="table" tablevalues="0 1" />
          <fefuncg type="table" tablevalues="0 0.27843137254902" />
          <fefuncb type="table" tablevalues="0.5921568627451 0.27843137254902" />
          <fefunca type="table" tablevalues="1 1" />
        </feComponentTransfer>
        <fecomposite in2="SourceGraphic" operator="in" />
      </filter>
    </defs>
  </svg><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;">
    <defs>
      <filter id="wp-duotone-midnight">
        <fecolormatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 " />
        <fecomponenttransfer color-interpolation-filters="sRGB">
          <fefuncr type="table" tablevalues="0 0" />
          <fefuncg type="table" tablevalues="0 0.64705882352941" />
          <fefuncb type="table" tablevalues="0 1" />
          <fefunca type="table" tablevalues="1 1" />
        </feComponentTransfer>
        <fecomposite in2="SourceGraphic" operator="in" />
      </filter>
    </defs>
  </svg><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;">
    <defs>
      <filter id="wp-duotone-magenta-yellow">
        <fecolormatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 " />
        <fecomponenttransfer color-interpolation-filters="sRGB">
          <fefuncr type="table" tablevalues="0.78039215686275 1" />
          <fefuncg type="table" tablevalues="0 0.94901960784314" />
          <fefuncb type="table" tablevalues="0.35294117647059 0.47058823529412" />
          <fefunca type="table" tablevalues="1 1" />
        </feComponentTransfer>
        <fecomposite in2="SourceGraphic" operator="in" />
      </filter>
    </defs>
  </svg><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;">
    <defs>
      <filter id="wp-duotone-purple-green">
        <fecolormatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 " />
        <fecomponenttransfer color-interpolation-filters="sRGB">
          <fefuncr type="table" tablevalues="0.65098039215686 0.40392156862745" />
          <fefuncg type="table" tablevalues="0 1" />
          <fefuncb type="table" tablevalues="0.44705882352941 0.4" />
          <fefunca type="table" tablevalues="1 1" />
        </feComponentTransfer>
        <fecomposite in2="SourceGraphic" operator="in" />
      </filter>
    </defs>
  </svg><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;">
    <defs>
      <filter id="wp-duotone-blue-orange">
        <fecolormatrix color-interpolation-filters="sRGB" type="matrix" values=" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 " />
        <fecomponenttransfer color-interpolation-filters="sRGB">
          <fefuncr type="table" tablevalues="0.098039215686275 1" />
          <fefuncg type="table" tablevalues="0 0.66274509803922" />
          <fefuncb type="table" tablevalues="0.84705882352941 0.41960784313725" />
          <fefunca type="table" tablevalues="1 1" />
        </feComponentTransfer>
        <fecomposite in2="SourceGraphic" operator="in" />
      </filter>
    </defs>
  </svg>
  <div data-elementor-type="wp-post" data-elementor-id="64448" class="elementor elementor-64448" data-elementor-settings="[]">
    <div class="elementor-section-wrap">
      <section class="elementor-section elementor-top-section elementor-element elementor-element-42fbfdb elementor-section-height-min-height elementor-section-items-top hidden elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="42fbfdb" data-element_type="section" id="home">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-72c6988e wdp-sticky-section-no" data-id="72c6988e" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section class="elementor-section elementor-inner-section elementor-element elementor-element-3936714a elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="3936714a" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <article data-dce-background-image-url="gambar/kajian-bg-fix-01.png" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-37cd5f51 wdp-sticky-section-no" data-id="37cd5f51" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <section class="elementor-section elementor-inner-section elementor-element elementor-element-5f12a8b4 elementor-section-full_width elementor-section-height-min-height elementor-section-height-default wdp-sticky-section-no" data-id="5f12a8b4" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default">
                          <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-6e7af0df wdp-sticky-section-no" data-id="6e7af0df" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-41278194 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="41278194" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Pernikahan Dari</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-66b43cec wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="66b43cec" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInLeft&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Khairil</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-3898b632 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="3898b632" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">dan</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-7da88cf6 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="7da88cf6" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInRight&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Riska</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-2fe62818 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="2fe62818" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">SABTU</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-3a3eef55 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="3a3eef55" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">15 | 07 | 2023</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-4f9a896e goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="4f9a896e" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                                <div class="elementor-widget-container">
                                  <img width="713" height="298" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-centre.png?fit=713%2C298&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga centre" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-centre.png?w=713&amp;ssl=1 713w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-centre.png?resize=150%2C63&amp;ssl=1 150w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-centre.png?resize=600%2C251&amp;ssl=1 600w" sizes="(max-width: 713px) 100vw, 713px">
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-66e1f4e wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="66e1f4e" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Kepada Yth.</h2>
                                </div>
                              </div>
                              <!-- <div class="elementor-element elementor-element-1178d6d7 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="1178d6d7" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Bapak/Ibu/Saudara/i</h2>
                                </div>
                              </div> -->
                              <div class="elementor-element elementor-element-4625aa63 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="4625aa63" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default"><?php echo $qu['kepada'];  ?></h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-592438b1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="592438b1" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Di Tempat</h2>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>
                      <div class="elementor-element elementor-element-78b07e56 elementor-absolute goyang-3 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="78b07e56" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="522" height="296" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?fit=522%2C296&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?w=522&amp;ssl=1 522w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?resize=150%2C85&amp;ssl=1 150w" sizes="(max-width: 522px) 100vw, 522px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-2e6944b7 elementor-absolute goyang-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="2e6944b7" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="469" height="586" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?fit=469%2C586&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?w=469&amp;ssl=1 469w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?resize=120%2C150&amp;ssl=1 120w" sizes="(max-width: 469px) 100vw, 469px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-4566c2c elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="4566c2c" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="386" height="351" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?fit=386%2C351&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?w=386&amp;ssl=1 386w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?resize=150%2C136&amp;ssl=1 150w" sizes="(max-width: 386px) 100vw, 386px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-1402a0a6 elementor-absolute goyang-3 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="1402a0a6" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-21,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="416" height="291" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?fit=416%2C291&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?w=416&amp;ssl=1 416w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?resize=150%2C105&amp;ssl=1 150w" sizes="(max-width: 416px) 100vw, 416px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-1d797010 elementor-absolute goyang-1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="1d797010" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="407" height="398" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?fit=407%2C398&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?w=407&amp;ssl=1 407w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?resize=150%2C147&amp;ssl=1 150w" sizes="(max-width: 407px) 100vw, 407px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-9d40fbc elementor-absolute goyang-1 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="9d40fbc" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-13,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="316" height="295" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?fit=316%2C295&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?w=316&amp;ssl=1 316w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?resize=150%2C140&amp;ssl=1 150w" sizes="(max-width: 316px) 100vw, 316px">
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>

      <!-- tes  -->


      <section class="elementor-section elementor-top-section elementor-element elementor-element-7513eb93 elementor-section-height-min-height elementor-section-items-top hidden elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="7513eb93" data-element_type="section" id="couple">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-794ab41d wdp-sticky-section-no" data-id="794ab41d" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section class="elementor-section elementor-inner-section elementor-element elementor-element-236b4733 elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="236b4733" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <article data-dce-background-image-url="https://i2.wp.com/einvite.id/wp-content/uploads/kajian-bg-fix-02.jpg" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-41649ba0 wdp-sticky-section-no" data-id="41649ba0" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <section class="elementor-section elementor-inner-section elementor-element elementor-element-1c7a5cd3 elementor-section-full_width elementor-section-height-min-height elementor-section-height-default wdp-sticky-section-no" data-id="1c7a5cd3" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default">
                          <div data-dce-background-color="#FFFFFF4D" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-1cc1e38b wdp-sticky-section-no" data-id="1cc1e38b" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-15890133 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="15890133" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">بِسْــــــــــــــــــمِ
                                    اللهِ الرَّحْمَنِ الرَّحِيْمِ</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-a66d9fc wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="a66d9fc" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Assalamu’alaikum Wr. Wb.
                                  </h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-1f3f351d wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="1f3f351d" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Maha suci Allah yang telah
                                    menciptakan mahluk-Nya berpasang-pasangan. Ya Allah, perkenankanlah kami
                                    merangkaikan kasih sayang yang Kau ciptakan diantara kami untuk mengikuti Sunnah
                                    Rasul-Mu dalam rangka membentuk keluarga yang sakinah, mawaddah, warahmah.</h2>
                                </div>
                              </div>


                              <!-- <section
                                class="elementor-section elementor-inner-section elementor-element elementor-element-5eae24bc elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
                                data-id="5eae24bc" data-element_type="section">
                                <div class="elementor-container elementor-column-gap-default">
                                  <div
                                    class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-f60cc74 wdp-sticky-section-no"
                                    data-id="f60cc74" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                      <div
                                        class="elementor-element elementor-element-a5c0eed zoom-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                                        data-id="a5c0eed" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;fadeInLeft&quot;}"
                                        data-widget_type="image.default">
                                        <div class="elementor-widget-container">
                                          <img width="350" height="350" src="gambar/riska.png"
                                            class="attachment-large size-large" alt="MW" loading="lazy"
                                            srcset="gambar/riska.png 350w, gambar/riska.png 144w"
                                            sizes="(max-width: 350px) 100vw, 350px"> </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div
                                    class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-7b784d11 wdp-sticky-section-no"
                                    data-id="7b784d11" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                      <div
                                        class="elementor-element elementor-element-694f1eb6 goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                                        data-id="694f1eb6" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                        data-widget_type="image.default">
                                        <div class="elementor-widget-container">
                                          <img width="20" height="35"
                                            src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-single.png?fit=20%2C35&amp;ssl=1"
                                            class="attachment-full size-full" alt="kajian bunga single" loading="lazy">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </section> -->
                              <div class="elementor-element elementor-element-64431ab7 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="64431ab7" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">

                                  <!-- <div
                                    class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-7186ddb4 wdp-sticky-section-no"
                                    data-id="7186ddb4" data-element_type="column">
                                    
                                  </div> -->
                                  <div class="elementor-widget-wrap elementor-element-populated">
                                    <div class="elementor-element elementor-element-4ec746ec zoom-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="4ec746ec" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInRight&quot;}" data-widget_type="image.default">
                                      <div class="elementor-widget-container">
                                        <style>
                                          #couple>div>div>div>section>div>article>div>section>div>div>div>div.elementor-element.elementor-element-64431ab7.wdp-sticky-section-no.elementor-widget.elementor-widget-heading.animated.zoomIn>div>div.elementor-widget-wrap.elementor-element-populated>div>div>img {
                                            width: 250px;
                                            margin-left: 13%;
                                            margin-bottom: -15%;
                                            margin-top: -15%;
                                          }
                                        </style>
                                        <img width="500" height="500" src="gambar/kairil.png" class="attachment-large size-large" alt="MW" loading="lazy" srcset="gambar/kairil.png 500w, gambar/kairil.png 144w" sizes="(max-width: 500px) 100vw, 500px">
                                      </div>
                                    </div>
                                  </div>
                                  <h2 class="elementor-heading-title elementor-size-default">Miftahul Khairil Anwar,
                                    S.kom</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-30629042 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="30629042" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Putra ke-3</h2>
                                  <h2 class="elementor-heading-title elementor-size-default"> Bapak Drs.Muhammad
                                    Jalaluddin
                                  </h2>
                                  <h2 class="elementor-heading-title elementor-size-default">Ibu Dra.Zuraidah ( Jujuk ) </h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-3a30543a elementor-icon-list--layout-inline elementor-align-center elementor-list-item-link-full_width wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-icon-list" data-id="3a30543a" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="icon-list.default">
                                <div class="elementor-widget-container">
                                  <ul class="elementor-icon-list-items elementor-inline-items">
                                    <li class="elementor-icon-list-item elementor-inline-item">
                                      <a href="https://www.instagram.com/m.khairil_anwar20/" target="_blank" rel="nofollow noopener">
                                        <span class="elementor-icon-list-icon">
                                          <i aria-hidden="true" class="fab fa-instagram"></i> </span>
                                        <span class="elementor-icon-list-text">@m.khairil_anwar20</span>
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-6becf82e elementor-widget-divider--view-line_icon elementor-view-default elementor-widget-divider--element-align-center wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-divider" data-id="6becf82e" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeIn&quot;}" data-widget_type="divider.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                      <div class="elementor-icon elementor-divider__element">
                                        <i aria-hidden="true" class="fas fa-heart"></i>
                                      </div>
                                    </span>
                                  </div>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-7384051b wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="7384051b" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-widget-wrap elementor-element-populated">
                                    <div class="elementor-element elementor-element-a5c0eed zoom-2 wdp-sticky-section-no elementor-widget elementor-widget-image animated fadeInLeft" data-id="a5c0eed" data-element_type="widget" data-settings="{}" data-widget_type="image.default">
                                      <div class="elementor-widget-container">
                                        <style>
                                          .elementor-64448 .elementor-element.elementor-element-a5c0eed img {
                                            width: 250px;
                                            margin-right: 15%;
                                            margin-bottom: -15%;
                                            margin-top: -15%;
                                          }
                                        </style>
                                        <img width="500" height="500" src="gambar/riska.png" class="attachment-large size-large" alt="MW" loading="lazy" srcset="gambar/riska.png 500w, gambar/riska.png 144w" sizes="(max-width: 500px) 100vw, 500px">
                                      </div>
                                    </div>
                                  </div>
                                  <h2 class="elementor-heading-title elementor-size-default">Riska Ananda Lestari</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-30629042 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="30629042" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Putri ke-3</h2>
                                  <h2 class="elementor-heading-title elementor-size-default">Bapak Sugianto Tas
                                  </h2>
                                  <h2 class="elementor-heading-title elementor-size-default">Ibu Erna Wati </h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-49338da elementor-icon-list--layout-inline elementor-align-center elementor-list-item-link-full_width wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-icon-list" data-id="49338da" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="icon-list.default">
                                <div class="elementor-widget-container">
                                  <ul class="elementor-icon-list-items elementor-inline-items">
                                    <li class="elementor-icon-list-item elementor-inline-item">
                                      <a href="https://www.instagram.com/attaqiy" target="_blank" rel="nofollow noopener">
                                        <span class="elementor-icon-list-icon">
                                          <i aria-hidden="true" class="fab fa-instagram"></i> </span>
                                        <span class="elementor-icon-list-text">@attaqiy</span>
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-659ea81 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="659ea81" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default"><i>“Dan di antara
                                      tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu
                                      sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya
                                      diantaramu rasa kasih dan sayang. Sesungguhnya pada yang demikian itu benar-benar
                                      terdapat tanda-tanda bagi kaum yang berfikir.”</i><br><br>
                                    <b>Al-Qur’an Surat Ar-Ruum (30:21)</b>
                                  </h2>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>
                      <div class="elementor-element elementor-element-650a81e elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="650a81e" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:300}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="359" height="382" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-02.png?fit=359%2C382&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga atas 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-02.png?w=359&amp;ssl=1 359w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-02.png?resize=141%2C150&amp;ssl=1 141w" sizes="(max-width: 359px) 100vw, 359px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-582b1210 elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="582b1210" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInLeft&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="373" height="356" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-03.png?fit=373%2C356&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga atas 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-03.png?w=373&amp;ssl=1 373w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-03.png?resize=150%2C143&amp;ssl=1 150w" sizes="(max-width: 373px) 100vw, 373px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-b957830 elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="b957830" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInLeft&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="370" height="317" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-01.png?fit=370%2C317&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga atas 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-01.png?w=370&amp;ssl=1 370w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-atas-01.png?resize=150%2C129&amp;ssl=1 150w" sizes="(max-width: 370px) 100vw, 370px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-eeb6d79 elementor-absolute goyang-1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="eeb6d79" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_position&quot;:&quot;absolute&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="336" height="252" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-03.png?fit=336%2C252&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga bawah 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-03.png?w=336&amp;ssl=1 336w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-03.png?resize=150%2C113&amp;ssl=1 150w" sizes="(max-width: 336px) 100vw, 336px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-c3bc707 elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="c3bc707" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_position&quot;:&quot;absolute&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="315" height="298" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-02.png?fit=315%2C298&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga bawah 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-02.png?w=315&amp;ssl=1 315w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-02.png?resize=150%2C142&amp;ssl=1 150w" sizes="(max-width: 315px) 100vw, 315px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-c2651ec elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="c2651ec" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_position&quot;:&quot;absolute&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="322" height="261" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-01.png?fit=322%2C261&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga bawah 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-01.png?w=322&amp;ssl=1 322w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-bawah-01.png?resize=150%2C122&amp;ssl=1 150w" sizes="(max-width: 322px) 100vw, 322px">
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>
      <section class="elementor-section elementor-top-section elementor-element elementor-element-277409f elementor-section-height-min-height elementor-section-items-top hidden elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="277409f" data-element_type="section" id="event">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-2aceb4c8 wdp-sticky-section-no" data-id="2aceb4c8" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section class="elementor-section elementor-inner-section elementor-element elementor-element-c9c76fb elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="c9c76fb" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <article data-dce-background-image-url="https://i2.wp.com/einvite.id/wp-content/uploads/kajian-bg-fix-01.jpg" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-32baf88e wdp-sticky-section-no" data-id="32baf88e" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">

                      <!-- tema animasi  -->
                      <section class="elementor-section elementor-inner-section elementor-element elementor-element-227f0d23 elementor-section-full_width elementor-section-height-min-height elementor-section-height-default wdp-sticky-section-no" data-id="227f0d23" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default">
                          <div data-dce-background-color="#FFFFFF63" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-3b57ea87 wdp-sticky-section-no" data-id="3b57ea87" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-c349f52 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="c349f52" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Acara Kami</h2>
                                </div>
                              </div>
                              <section class="elementor-section elementor-inner-section elementor-element elementor-element-b46189b elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no" data-id="b46189b" data-element_type="section">
                                <div class="elementor-container elementor-column-gap-default">
                                  <div data-dce-background-color="#FFFFFF7D" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-71a35700 wdp-sticky-section-no" data-id="71a35700" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-widget-wrap elementor-element-populated">

                                      <!-- akad kami  -->
                                      <div class="elementor-element elementor-element-77b9f386 naik-turun elementor-view-default wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="77b9f386" data-element_type="widget" data-widget_type="icon.default">
                                        <div class="elementor-widget-container">
                                          <div class="elementor-icon-wrapper">
                                            <div class="elementor-icon">
                                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" viewbox="0 0 422.5 422.5" style="enable-background:new 0 0 422.5 422.5;" xml:space="preserve">
                                                <g>
                                                  <g>
                                                    <path d="M281.538,177.828c-25.486,0-49.659,7.705-70.3,22.258c-14.168-9.979-30.54-17.031-48.229-20.264l47.805-31.299    c2.93-1.918,4.142-5.607,2.921-8.891l-14.211-38.193c-1.093-2.938-3.896-4.885-7.029-4.885H89.986    c-3.133,0-5.937,1.947-7.029,4.885l-14.213,38.193c-1.221,3.281-0.009,6.973,2.921,8.891l47.68,31.217    C62.163,189.977,18.626,240.08,18.626,300.162c0,67.457,54.88,122.338,122.336,122.338c25.456,0,49.681-7.688,70.332-22.246    c19.894,14.004,44.121,22.246,70.244,22.246c67.456,0,122.336-54.881,122.336-122.338    C403.874,232.707,348.994,177.828,281.538,177.828z M33.626,300.162c0-59.186,48.15-107.334,107.336-107.334    c59.186,0,107.336,48.148,107.336,107.334c0,19.404-5.169,38.172-14.977,54.691c-5.201-4.592-9.726-9.924-13.453-15.807    c5.931-12.033,9.055-25.379,9.055-38.885c0-48.502-39.459-87.959-87.961-87.959c-48.502,0-87.961,39.457-87.961,87.959    c0,48.502,39.459,87.963,87.961,87.963c15.852,0,31.14-4.186,44.629-12.154c4.093,5.17,8.598,9.996,13.456,14.441    c-17.302,11.189-37.217,17.088-58.085,17.088C81.776,407.5,33.626,359.348,33.626,300.162z M140.962,373.125    c-40.23,0-72.961-32.732-72.961-72.963c0-40.23,32.73-72.959,72.961-72.959c13.076,0,25.349,3.475,35.974,9.523    c-11.614,19.092-17.733,40.853-17.733,63.436c0,23.217,6.501,44.941,17.777,63.455    C166.041,369.848,153.725,373.125,140.962,373.125z M211.254,280.6c1.737,6.227,2.669,12.787,2.669,19.563    c0,6.629-0.925,13.205-2.683,19.541c-1.733-6.221-2.663-12.773-2.663-19.541C208.577,293.525,209.493,286.939,211.254,280.6z     M245.52,236.717c10.94-6.234,23.256-9.514,36.019-9.514c40.23,0,72.961,32.728,72.961,72.959c0,40.23-32.73,72.963-72.961,72.963    c-13.077,0-25.351-3.477-35.976-9.525c11.61-19.098,17.735-40.893,17.735-63.438C263.298,276.947,256.794,255.227,245.52,236.717z     M179.028,134.748h-75.456l37.824-22.01L179.028,134.748z M187.282,111.555l5.063,13.606l-23.262-13.606H187.282z M95.198,111.555    h18.407L90.11,125.227L95.198,111.555z M100.921,149.748h80.637l-40.318,26.398L100.921,149.748z M281.538,407.5    c-59.186,0-107.336-48.152-107.336-107.338c0-19.436,5.17-38.174,14.982-54.686c5.199,4.59,9.722,9.92,13.447,15.803    c-5.93,12.033-9.055,25.377-9.055,38.883c0,48.502,39.459,87.963,87.961,87.963c48.502,0,87.961-39.461,87.961-87.963    c0-48.502-39.459-87.959-87.961-87.959c-15.853,0-31.142,4.186-44.633,12.154c-4.09-5.166-8.592-9.99-13.447-14.434    c17.293-11.191,37.182-17.096,58.08-17.096c59.186,0,107.336,48.148,107.336,107.334C388.874,359.348,340.724,407.5,281.538,407.5    z">
                                                    </path>
                                                    <path d="M141.238,49.291c4.143,0,7.5-3.357,7.5-7.5V7.5c0-4.143-3.357-7.5-7.5-7.5c-4.143,0-7.5,3.357-7.5,7.5v34.291    C133.738,45.934,137.096,49.291,141.238,49.291z">
                                                    </path>
                                                    <path d="M57.964,80.623c1.465,1.465,3.384,2.197,5.304,2.197c1.919,0,3.839-0.732,5.303-2.197c2.93-2.928,2.93-7.678,0.001-10.605    L44.325,45.77c-2.929-2.928-7.678-2.93-10.606,0c-2.93,2.928-2.93,7.678-0.001,10.605L57.964,80.623z">
                                                    </path>
                                                    <path d="M219.211,82.82c1.919,0,3.839-0.732,5.304-2.197l24.246-24.248c2.929-2.928,2.929-7.678-0.001-10.605    c-2.928-2.928-7.677-2.93-10.606,0l-24.246,24.248c-2.929,2.928-2.929,7.678,0.001,10.605    C215.372,82.088,217.292,82.82,219.211,82.82z">
                                                    </path>
                                                  </g>
                                                </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                              </svg>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="elementor-element elementor-element-4464bc1f wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="4464bc1f" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Akad Nikah</h2>
                                        </div>

                                        <!-- request dari kairil  -->
                                        <div>
                                          <div class="elementor-column elementor-inner-column elementor-element elementor-element-2ae44c79 animated-slow wdp-sticky-section-no animated fadeInLeft" data-id="2ae44c79" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;gradient&quot;,&quot;animation&quot;:&quot;fadeInLeft&quot;}">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div class="elementor-element elementor-element-14ba03bb elementor-view-framed elementor-shape-circle elementor-mobile-position-top elementor-vertical-align-top wdp-sticky-section-no elementor-widget elementor-widget-icon-box" data-id="14ba03bb" data-element_type="widget" data-widget_type="icon-box.default">
                                                <div class="elementor-widget-container">
                                                  <link rel="stylesheet" href="https://turutmengundang.my.id/wp-content/plugins/elementor/assets/css/widget-icon-box.min.css">
                                                  <div class="elementor-icon-box-wrapper">
                                                    <div class="elementor-icon-box-icon">
                                                      <span class="elementor-icon elementor-animation-">
                                                        <i aria-hidden="true" class="far fa-calendar-alt"></i> </span>
                                                    </div>
                                                    <div class="elementor-icon-box-content">
                                                      <p class="elementor-icon-box-title">
                                                        <span>
                                                          Minggu, 30 April 2023 </span>
                                                      </p>
                                                      <p class="elementor-icon-box-description">
                                                        09.00 WIB s/d Selesai </p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="elementor-element elementor-element-6aa099d1 elementor-view-framed elementor-shape-circle elementor-mobile-position-top elementor-vertical-align-top wdp-sticky-section-no elementor-widget elementor-widget-icon-box" data-id="6aa099d1" data-element_type="widget" data-widget_type="icon-box.default">
                                                <div class="elementor-widget-container">
                                                  <div class="elementor-icon-box-wrapper">
                                                    <div class="elementor-icon-box-icon">
                                                      <span class="elementor-icon elementor-animation-">
                                                        <i aria-hidden="true" class="fas fa-map-marked-alt"></i> </span>
                                                    </div>
                                                    <div class="elementor-icon-box-content">
                                                      <p class="elementor-icon-box-title">
                                                        <span>
                                                          Kediaman Mempelai Wanita </span>
                                                      </p>
                                                      <p class="elementor-icon-box-description">
                                                        Paya bakung pasar 1 c luar Gg. Pandu 1 </p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- <section
                                        class="elementor-section elementor-inner-section elementor-element elementor-element-3f8d4cd0 elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
                                        data-id="3f8d4cd0" data-element_type="section">
                                        <div class="elementor-container elementor-column-gap-default">
                                          <div
                                            class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-30821b3d wdp-sticky-section-no"
                                            data-id="30821b3d" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-ef1fffe wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                                data-id="ef1fffe" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="heading.default">
                                                <div class="elementor-widget-container">
                                                  <h2 class="elementor-heading-title elementor-size-default">Minggu</h2>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div
                                            class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-d9103ee wdp-sticky-section-no"
                                            data-id="d9103ee" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-24c4266 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-counter"
                                                data-id="24c4266" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="counter.default">
                                                <div class="elementor-widget-container">
                                                  <div class="elementor-counter">
                                                    <div class="elementor-counter-number-wrapper">
                                                      <span class="elementor-counter-number-prefix"></span>
                                                      <span class="elementor-counter-number" data-duration="2000"
                                                        data-to-value="26" data-from-value="0">30</span>
                                                      <span class="elementor-counter-number-suffix"></span>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div
                                                class="elementor-element elementor-element-33f8c226 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                                data-id="33f8c226" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="heading.default">
                                                <div class="elementor-widget-container">
                                                  <h2 class="elementor-heading-title elementor-size-default">April
                                                  </h2>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div
                                            class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-5c18f62 wdp-sticky-section-no"
                                            data-id="5c18f62" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-12be5901 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                                data-id="12be5901" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="heading.default">
                                                <div class="elementor-widget-container">
                                                  <h2 class="elementor-heading-title elementor-size-default">2023</h2>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </section> -->
                                            <!-- <div
                                        class="elementor-element elementor-element-108bca95 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="108bca95" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Pukul 09.00 WIB -
                                            Selesai</h2>
                                        </div>
                                      </div>
                                      <div
                                        class="elementor-element elementor-element-4d766a72 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="4d766a72" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Alamat</h2>
                                        </div>
                                      </div>
                                      <div
                                        class="elementor-element elementor-element-48a6a2fb wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="48a6a2fb" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">paya bakung pasar 1
                                            c luar Gg. Pandu 1
                                          </h2>
                                        </div>
                                      </div>
                                      <div
                                        class="elementor-element elementor-element-4334b0e7 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="4334b0e7" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                        data-widget_type="heading.default">
                                      </div> -->
                                            <!-- <div data-dce-background-color="#477D59"
                                        class="elementor-element elementor-element-3a206965 elementor-align-center zoom-1 wdp-sticky-section-no elementor-widget elementor-widget-button"
                                        data-id="3a206965" data-element_type="widget" data-widget_type="button.default">
                                        <div class="elementor-widget-container">
                                          <div class="elementor-button-wrapper">
                                            <a class="elementor-button elementor-size-sm" role="button">
                                              <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-icon elementor-align-icon-left">
                                                  <i aria-hidden="true" class="fas fa-map-marker-alt"></i> </span>
                                                <span class="elementor-button-text">Lihat Lokasi</span>
                                              </span>
                                            </a>
                                          </div>
                                        </div>
                                      </div> -->



                                            <!-- <div
                                                class="elementor-element elementor-element-1df892ab elementor-align-center wdp-sticky-section-no elementor-widget elementor-widget-button"
                                                data-id="1df892ab" data-element_type="widget"
                                                data-widget_type="button.default">
                                                <div class="elementor-widget-container">
                                                  <div class="elementor-button-wrapper">
                                                    <a href="https://goo.gl/maps/FsxbuLNK6hddTpCD9" target="_blank"
                                                      class="elementor-button-link elementor-button elementor-size-xs"
                                                      role="button">
                                                      <span class="elementor-button-content-wrapper">
                                                        <span class="elementor-button-icon elementor-align-icon-left">
                                                          <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                                        </span>
                                                        <span class="elementor-button-text">Buka Google Maps</span>
                                                      </span>
                                                    </a>
                                                  </div>
                                                </div>
                                              </div> -->
                                          </div>
                                        </div>
                                        <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-e4487dd animated-slow wdp-sticky-section-no animated fadeInRight" data-id="e4487dd" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;gradient&quot;,&quot;animation&quot;:&quot;fadeInRight&quot;}">
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </section>
                              <section class="elementor-section elementor-inner-section elementor-element elementor-element-541fb534 elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no" data-id="541fb534" data-element_type="section">
                                <div class="elementor-container elementor-column-gap-default">
                                  <div data-dce-background-color="#FFFFFF7D" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-2ff47199 wdp-sticky-section-no" data-id="2ff47199" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                      <div class="elementor-element elementor-element-64d9ec43 naik-turun elementor-view-default wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="64d9ec43" data-element_type="widget" data-widget_type="icon.default">
                                        <div class="elementor-widget-container">
                                          <div class="elementor-icon-wrapper">
                                            <div class="elementor-icon">
                                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" viewbox="0 0 371.137 371.137" style="enable-background:new 0 0 371.137 371.137;" xml:space="preserve">
                                                <g>
                                                  <g>
                                                    <path d="M278.71,257.014c-1.209-2.154-3.4-3.574-5.861-3.797c-7.23-0.658-14.15-0.992-20.563-0.992    c-26.504,0-46.094,5.623-58.395,16.725v-69.205c1.643,0.094,3.301,0.148,4.973,0.148c21.566,0,40.996-7.604,53.303-20.859    c11.082-11.936,16.197-28.24,14.793-47.152c-1.668-22.441-7.867-33.361-13.336-42.996c-2.457-4.326-4.777-8.414-6.533-13.359    c-8.797-24.748,9.123-48.604,9.293-48.826c2.01-2.596,2.096-6.197,0.213-8.889c-1.883-2.689-5.297-3.842-8.424-2.846    c-1.029,0.328-8.627,2.801-19.43,7.52L205.548,1.891c-1.879-1.668-4.471-2.283-6.896-1.643    c-34.541,9.127-50.49,19.084-56.113,23.295c-1.664-0.685-16.861-6.146-20.303-7.141c-6.756-1.951-8.365-2.416-11.193-0.967    c-2.092,1.072-3.559,3.062-3.967,5.375c-0.406,2.314,0.295,4.686,1.895,6.406c0.172,0.186,17.047,18.717,8.842,42.725    c-0.912,2.67-2.262,5.783-3.824,9.391c-5.393,12.449-12.779,29.498-12.832,52.51c-0.043,18.402,5.838,33.604,17.475,45.18    c18.912,18.813,46.525,21.932,60.262,22.225v46.678c-12.164-12.135-32.324-18.279-60.039-18.279    c-6.414,0-13.332,0.334-20.564,0.992c-2.459,0.225-4.65,1.644-5.859,3.799c-1.209,2.152-1.281,4.764-0.191,6.98    c19.918,40.506,42.416,61.045,66.871,61.049c0.004,0,0.006,0,0.008,0c8.037,0,14.775-2.352,19.775-4.953v68.125    c0,4.142,3.357,7.5,7.5,7.5c4.143,0,7.5-3.358,7.5-7.5v-42.729c4.797,2.256,10.947,4.137,18.135,4.137    c24.455,0,46.955-20.541,66.873-61.051C279.989,261.778,279.919,259.168,278.71,257.014z M176.307,279.493    c-2.834,2.133-9.061,5.973-17.191,5.973c-0.002,0-0.004,0-0.004,0c-16.17-0.002-32.715-14.695-48.191-42.648    c2.727-0.113,5.375-0.172,7.932-0.172c25.262,0,43.123,5.656,51.648,16.357C176.714,266.799,176.714,275.532,176.307,279.493z     M198.618,15.797l15.391,13.664c-9.154,4.684-19.225,10.527-29.018,17.564c-0.121-0.098-0.238-0.199-0.367-0.293    c-8.836-6.357-17.699-11.539-25.816-15.693 M129.208,166.387c-8.693-8.649-13.086-20.26-13.053-34.51    c0.045-19.92,6.449-34.703,11.596-46.582c1.619-3.738,3.148-7.268,4.254-10.502c5.365-15.697,2.928-29.51-1.121-39.693    c11.277,4.219,26.525,11.098,41.82,21.592c-14.566,12.596-27.182,28.147-33.248,46.799c-10.314,31.723-4.131,53.391,2.873,65.98    c1.926,3.465,4.021,6.436,6.094,8.979C141.575,175.793,134.804,171.954,129.208,166.387z M169.18,177.329    c-0.156-0.107-0.27-0.18-0.434-0.275c-1.279-0.768-31.145-19.352-15.025-68.924c11.875-36.523,55.395-60.484,80.609-71.627    c-4.385,11.609-7.295,27.391-1.375,44.047c2.197,6.184,4.955,11.043,7.623,15.742c5.145,9.063,10.004,17.621,11.422,36.701    c1.104,14.854-2.539,26.91-10.826,35.836c-9.479,10.209-24.9,16.064-42.311,16.064    C187.184,184.893,176.089,182.065,169.18,177.329z M212.026,310.045c-8.143,0-14.379-3.852-17.191-5.969    c-0.412-3.992-0.4-12.709,5.803-20.494c8.525-10.701,26.385-16.357,51.648-16.357c2.557,0,5.205,0.057,7.932,0.172    C244.739,295.352,228.194,310.045,212.026,310.045z">
                                                    </path>
                                                  </g>
                                                </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                                <g> </g>
                                              </svg>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="elementor-element elementor-element-32b1a281 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="32b1a281" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Resepsi</h2>
                                        </div>
                                      </div>

                                      <!-- tidak diminta  -->
                                      <!-- <section
                                        class="elementor-section elementor-inner-section elementor-element elementor-element-3c1ba52a elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
                                        data-id="3c1ba52a" data-element_type="section">
                                        <div class="elementor-container elementor-column-gap-default">
                                          <div
                                            class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-4a5e768b wdp-sticky-section-no"
                                            data-id="4a5e768b" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-bc4a92f wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                                data-id="bc4a92f" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="heading.default">
                                                <div class="elementor-widget-container">
                                                  <h2 class="elementor-heading-title elementor-size-default">Sabtu</h2>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div
                                            class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-50edc45e wdp-sticky-section-no"
                                            data-id="50edc45e" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-553f06c wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-counter"
                                                data-id="553f06c" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="counter.default">
                                                <div class="elementor-widget-container">
                                                  <div class="elementor-counter">
                                                    <div class="elementor-counter-number-wrapper">
                                                      <span class="elementor-counter-number-prefix"></span>
                                                      <span class="elementor-counter-number" data-duration="3000"
                                                        data-to-value="26" data-from-value="0">17</span>
                                                      <span class="elementor-counter-number-suffix"></span>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div
                                                class="elementor-element elementor-element-cfefe74 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                                data-id="cfefe74" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="heading.default">
                                                <div class="elementor-widget-container">
                                                  <h2 class="elementor-heading-title elementor-size-default">July
                                                  </h2>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div
                                            class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-2be15046 wdp-sticky-section-no"
                                            data-id="2be15046" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-3d2e891a wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                                data-id="3d2e891a" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="heading.default">
                                                <div class="elementor-widget-container">
                                                  <h2 class="elementor-heading-title elementor-size-default">2023</h2>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </section>
                                      <div
                                        class="elementor-element elementor-element-5a6c9648 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="5a6c9648" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Pukul 11.00-14.00
                                            WIB</h2>
                                        </div>
                                      </div>
                                      <div
                                        class="elementor-element elementor-element-802eb20 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="802eb20" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Alamat</h2>
                                        </div>
                                      </div>
                                      <div
                                        class="elementor-element elementor-element-48b6d28a wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="48b6d28a" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Mesjid Ashobirin
                                          </h2>
                                        </div>
                                      </div>
                                      <div
                                        class="elementor-element elementor-element-18980436 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="18980436" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Jl.kapten b
                                            sihombing/pasar 5 timur medan estate</h2>
                                        </div>
                                      </div>
                                      <div data-dce-background-color="#477D59"
                                        class="elementor-element elementor-element-14362172 elementor-align-center zoom-1 wdp-sticky-section-no elementor-widget elementor-widget-button"
                                        data-id="14362172" data-element_type="widget" data-widget_type="button.default">
                                        <div class="elementor-widget-container">
                                          <div class="elementor-button-wrapper">
                                            <a href="https://goo.gl/maps/hEurdfFaLmW7uagK6" target="_blank"
                                              class="elementor-button elementor-size-sm" role="button">
                                              <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-icon elementor-align-icon-left">
                                                  <i aria-hidden="true" class="fas fa-map-marker-alt"></i> </span>
                                                <span class="elementor-button-text">Lihat Lokasi</span>
                                              </span>
                                            </a>
                                          </div>
                                        </div>
                                      </div>
                                      <div
                                        class="elementor-element elementor-element-aad3c4f wdp-sticky-section-no elementor-widget elementor-widget-weddingpress-countdown"
                                        data-id="aad3c4f" data-element_type="widget"
                                        data-widget_type="weddingpress-countdown.default">
                                        <div class="elementor-widget-container">
                                          <div class="wpkoi-elements-countdown-wrapper">
                                            <div
                                              class="wpkoi-elements-countdown-container wpkoi-elements-countdown-label-block ">
                                              <ul id="wpkoi-elements-countdown-aad3c4f"
                                                class="wpkoi-elements-countdown-items" data-date="Jul 15 2023 0:00:00">
                                                <li class="wpkoi-elements-countdown-item">
                                                  <div class="wpkoi-elements-countdown-days"><span data-days
                                                      class="wpkoi-elements-countdown-digits">00</span><span
                                                      class="wpkoi-elements-countdown-label">Hari</span></div>
                                                </li>
                                                <li class="wpkoi-elements-countdown-item">
                                                  <div class="wpkoi-elements-countdown-hours"><span data-hours
                                                      class="wpkoi-elements-countdown-digits">00</span><span
                                                      class="wpkoi-elements-countdown-label">Jam</span></div>
                                                </li>
                                                <li class="wpkoi-elements-countdown-item">
                                                  <div class="wpkoi-elements-countdown-minutes"><span data-minutes
                                                      class="wpkoi-elements-countdown-digits">00</span><span
                                                      class="wpkoi-elements-countdown-label">Menit</span></div>
                                                </li>
                                                <li class="wpkoi-elements-countdown-item">
                                                  <div class="wpkoi-elements-countdown-seconds"><span data-seconds
                                                      class="wpkoi-elements-countdown-digits">00</span><span
                                                      class="wpkoi-elements-countdown-label">Detik</span></div>
                                                </li>
                                              </ul>
                                              <div class="clearfix"></div>
                                            </div>
                                          </div>
                                          <script type="rocketlazyloadscript" data-rocket-type="text/javascript">
                                            jQuery(document).ready(function ($) {
		'use strict';
		$("#wpkoi-elements-countdown-aad3c4f").countdown();
	});
	</script>
                                        </div>
                                      </div> -->

                                      <!-- request kairil resepsi  -->
                                      <div class="elementor-widget-wrap elementor-element-populated animated-slow wdp-sticky-section-no animated fadeInLeft"
                                      data-id="2ae44c79" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;gradient&quot;,&quot;animation&quot;:&quot;fadeInLeft&quot;}">
                                        <div class="elementor-element elementor-element-5777a2c7 elementor-view-framed elementor-shape-circle elementor-mobile-position-top elementor-vertical-align-top wdp-sticky-section-no elementor-widget elementor-widget-icon-box" data-id="5777a2c7" data-element_type="widget" data-widget_type="icon-box.default">
                                          <div class="elementor-widget-container">
                                            <div class="elementor-icon-box-wrapper">
                                              <div class="elementor-icon-box-icon">
                                                <span class="elementor-icon elementor-animation-">
                                                  <i aria-hidden="true" class="far fa-calendar-alt"></i> </span>
                                              </div>
                                              <div class="elementor-icon-box-content">
                                                <p class="elementor-icon-box-title">
                                                  <span>
                                                    Sabtu, 15 July 2023 </span>
                                                </p>
                                                <p class="elementor-icon-box-description">
                                                  11.00 WIB s/d Selesai </p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="elementor-element elementor-element-aad3c4f wdp-sticky-section-no elementor-widget elementor-widget-weddingpress-countdown" data-id="aad3c4f" data-element_type="widget" data-widget_type="weddingpress-countdown.default">
                                          <div class="elementor-widget-container">
                                            <div class="wpkoi-elements-countdown-wrapper">
                                              <div class="wpkoi-elements-countdown-container wpkoi-elements-countdown-label-block ">
                                                <ul id="wpkoi-elements-countdown-aad3c4f" class="wpkoi-elements-countdown-items" data-date="Jul 15 2023 0:00:00">
                                                  <li class="wpkoi-elements-countdown-item">
                                                    <div class="wpkoi-elements-countdown-days"><span data-days class="wpkoi-elements-countdown-digits">00</span><span class="wpkoi-elements-countdown-label">Hari</span></div>
                                                  </li>
                                                  <li class="wpkoi-elements-countdown-item">
                                                    <div class="wpkoi-elements-countdown-hours"><span data-hours class="wpkoi-elements-countdown-digits">00</span><span class="wpkoi-elements-countdown-label">Jam</span></div>
                                                  </li>
                                                  <li class="wpkoi-elements-countdown-item">
                                                    <div class="wpkoi-elements-countdown-minutes"><span data-minutes class="wpkoi-elements-countdown-digits">00</span><span class="wpkoi-elements-countdown-label">Menit</span></div>
                                                  </li>
                                                  <li class="wpkoi-elements-countdown-item">
                                                    <div class="wpkoi-elements-countdown-seconds"><span data-seconds class="wpkoi-elements-countdown-digits">00</span><span class="wpkoi-elements-countdown-label">Detik</span></div>
                                                  </li>
                                                </ul>
                                                <div class="clearfix"></div>
                                              </div>
                                            </div>
                                            <script type="rocketlazyloadscript" data-rocket-type="text/javascript">
                                              jQuery(document).ready(function ($) {
                                              'use strict';
                                              $("#wpkoi-elements-countdown-aad3c4f").countdown();
                                            });
                                            </script>
                                          </div>
                                        </div>

                                        <div class="elementor-element elementor-element-133a5db9 elementor-view-framed elementor-shape-circle elementor-mobile-position-top elementor-vertical-align-top wdp-sticky-section-no elementor-widget elementor-widget-icon-box" data-id="133a5db9" data-element_type="widget" data-widget_type="icon-box.default">
                                          <div class="elementor-widget-container">
                                            <div class="elementor-icon-box-wrapper">
                                              <div class="elementor-icon-box-icon">
                                                <span class="elementor-icon elementor-animation-">
                                                  <i aria-hidden="true" class="fas fa-map-marked-alt"></i> </span>
                                              </div>
                                              <div class="elementor-icon-box-content">
                                                <p class="elementor-icon-box-title">
                                                  <span>
                                                    Masjid Ash Shobirin </span>
                                                </p>
                                                <p class="elementor-icon-box-description">
                                                  Jl.Kapten b Sihombing/pasar 5 timur Medan Estate </p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="elementor-element elementor-element-3f479106 elementor-align-center wdp-sticky-section-no elementor-widget elementor-widget-button" data-id="3f479106" data-element_type="widget" data-widget_type="button.default">
                                          <div class="elementor-widget-container">
                                            <div class="elementor-button-wrapper">
                                              <a href="https://goo.gl/maps/v7ZrvTMbH9hw9sCg7" target="_blank" class="elementor-button-link elementor-button elementor-size-xs" role="button">
                                                <span class="elementor-button-content-wrapper">
                                                  <span class="elementor-button-icon elementor-align-icon-left">
                                                    <i aria-hidden="true" class="fas fa-map-marker-alt"></i> </span>
                                                  <span class="elementor-button-text">Buka Google Maps</span>
                                                </span>
                                              </a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <div data-dce-background-color="#477D59" class="elementor-element elementor-element-b188621 zoom-1 elementor-align-center wdp-sticky-section-no elementor-widget elementor-widget-weddingpress-datekit" data-id="b188621" data-element_type="widget" data-widget_type="weddingpress-datekit.default">
                                        <div class="elementor-widget-container">
                                          <div class="elementor-button-wrapper">
                                            <a href="https://calendar.google.com/calendar/r/eventedit?text=Acara+Nikah+Khairil+Dan+Riska&dates=20230714T230000Z/20230714T23T030000Z&details=For+details,+link+here:+https://www.google.com/maps/place/Masjid+Ash+-+Shobirin/@3.6925164,98.6791312,16.75z/data=!4m6!3m5!1s0x303132c848192bfb:0x559f671ae9d4f5cd!8m2!3d3.6926235!4d98.6835437!16s%2Fg%2F11btx09y1k" class="elementor-button-link elementor-button elementor-size-sm" target="_blank" rel="nofollow" role="button">
                                              <span class="elementor-button-content-wrapper wdp-flexbox">
                                                <span class="elementor-button-icon elementor-align-icon-left">
                                                  <i aria-hidden="true" class="far fa-calendar-check"></i> </span>
                                                <span class="elementor-button-text">Simpan di Kalender</span>
                                              </span>
                                            </a>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </section>
                            </div>
                          </div>
                        </div>
                      </section>
                      <div class="elementor-element elementor-element-419fc33 elementor-absolute goyang-3 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="419fc33" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="522" height="296" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?fit=522%2C296&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?w=522&amp;ssl=1 522w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?resize=150%2C85&amp;ssl=1 150w" sizes="(max-width: 522px) 100vw, 522px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-b8b98f3 elementor-absolute goyang-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="b8b98f3" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="469" height="586" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?fit=469%2C586&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?w=469&amp;ssl=1 469w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?resize=120%2C150&amp;ssl=1 120w" sizes="(max-width: 469px) 100vw, 469px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-35b01f6 elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="35b01f6" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="386" height="351" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?fit=386%2C351&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?w=386&amp;ssl=1 386w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?resize=150%2C136&amp;ssl=1 150w" sizes="(max-width: 386px) 100vw, 386px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-37424e6 elementor-absolute goyang-3 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="37424e6" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-21,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="416" height="291" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?fit=416%2C291&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?w=416&amp;ssl=1 416w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?resize=150%2C105&amp;ssl=1 150w" sizes="(max-width: 416px) 100vw, 416px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-1c095ed elementor-absolute goyang-1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="1c095ed" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="407" height="398" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?fit=407%2C398&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?w=407&amp;ssl=1 407w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?resize=150%2C147&amp;ssl=1 150w" sizes="(max-width: 407px) 100vw, 407px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-7696c97 elementor-absolute goyang-1 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="7696c97" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-13,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="316" height="295" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?fit=316%2C295&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?w=316&amp;ssl=1 316w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?resize=150%2C140&amp;ssl=1 150w" sizes="(max-width: 316px) 100vw, 316px">
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>

      <!-- lokasi maps  -->
      <section class="elementor-section elementor-top-section elementor-element elementor-element-ab4dd24 elementor-section-height-min-height elementor-section-items-top hidden elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="ab4dd24" data-element_type="section" id="map">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-554fe92 wdp-sticky-section-no" data-id="554fe92" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section class="elementor-section elementor-inner-section elementor-element elementor-element-fee5b00 elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="fee5b00" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <article data-dce-background-image-url="https://i2.wp.com/einvite.id/wp-content/uploads/kajian-bg-fix-02.jpg" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-b7ba243 wdp-sticky-section-no" data-id="b7ba243" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <section class="elementor-section elementor-inner-section elementor-element elementor-element-6f75064 elementor-section-full_width elementor-section-height-min-height elementor-section-height-default wdp-sticky-section-no" data-id="6f75064" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default">
                          <div data-dce-background-color="#FFFFFF63" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-b9fbb2c wdp-sticky-section-no" data-id="b9fbb2c" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-f27a9a4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="f27a9a4" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Lokasi Maps​</h2>
                                </div>
                              </div>
                              <section class="elementor-section elementor-inner-section elementor-element elementor-element-15af3ab elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no" data-id="15af3ab" data-element_type="section">
                                <div class="elementor-container elementor-column-gap-default">
                                  <div data-dce-background-color="#FFFFFF7D" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-b55f80a wdp-sticky-section-no" data-id="b55f80a" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                      <div class="elementor-element elementor-element-3ba6ed3 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-google_maps" data-id="3ba6ed3" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="google_maps.default">
                                        <div class="elementor-widget-container">
                                          <div class="elementor-custom-embed">
                                            <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q= Masjid Ash Shobirin, Jl. Kapten Batu Sihombing, Medan Estate, Kec. Percut Sei Tuan, Kabupaten Deli Serdang, Sumatera Utara 20371&#038;t=m&#038;z=16&#038;output=embed&#038;iwloc=near" title="Masjid Ash Shobirin" aria-label="Masjid Ash Shobirin"></iframe>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </section>

                              <!-- section protokol kesehatan  -->
                              <!-- <section
                                class="elementor-section elementor-inner-section elementor-element elementor-element-770f9bf elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
                                data-id="770f9bf" data-element_type="section">
                                <div class="elementor-container elementor-column-gap-default">
                                  <div data-dce-background-color="#FFFFFF7D"
                                    class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-94b8366 wdp-sticky-section-no"
                                    data-id="94b8366" data-element_type="column"
                                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                      <div
                                        class="elementor-element elementor-element-a8e57ff wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="a8e57ff" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Protokol Kesehatan
                                          </h2>
                                        </div>
                                      </div>
                                      <section
                                        class="elementor-section elementor-inner-section elementor-element elementor-element-c371a7f elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
                                        data-id="c371a7f" data-element_type="section">
                                        <div class="elementor-container elementor-column-gap-default">
                                          <div
                                            class="elementor-column elementor-col-25 elementor-inner-column elementor-element elementor-element-64ee0c1 wdp-sticky-section-no"
                                            data-id="64ee0c1" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-77cee5a wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                                                data-id="77cee5a" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="image.default">
                                                <div class="elementor-widget-container">
                                                  <figure class="wp-caption">
                                                    <img width="150" height="150"
                                                      src="https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/1-MEMAKAI-MASKER.png?fit=150%2C150&amp;ssl=1"
                                                      class="attachment-thumbnail size-thumbnail" alt="1 MEMAKAI MASKER"
                                                      loading="lazy"
                                                      srcset="https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/1-MEMAKAI-MASKER.png?w=535&amp;ssl=1 535w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/1-MEMAKAI-MASKER.png?resize=300%2C300&amp;ssl=1 300w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/1-MEMAKAI-MASKER.png?resize=150%2C150&amp;ssl=1 150w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/1-MEMAKAI-MASKER.png?resize=100%2C100&amp;ssl=1 100w"
                                                      sizes="(max-width: 150px) 100vw, 150px">
                                                    <figcaption class="widget-image-caption wp-caption-text">Memakai
                                                      Masker</figcaption>
                                                  </figure>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div
                                            class="elementor-column elementor-col-25 elementor-inner-column elementor-element elementor-element-160d0c0 wdp-sticky-section-no"
                                            data-id="160d0c0" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-034e9ac wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                                                data-id="034e9ac" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="image.default">
                                                <div class="elementor-widget-container">
                                                  <figure class="wp-caption">
                                                    <img width="150" height="150"
                                                      src="https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/2-CEK-SUHU-TUBUH.png?fit=150%2C150&amp;ssl=1"
                                                      class="attachment-thumbnail size-thumbnail" alt="2 CEK SUHU TUBUH"
                                                      loading="lazy"
                                                      srcset="https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/2-CEK-SUHU-TUBUH.png?w=535&amp;ssl=1 535w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/2-CEK-SUHU-TUBUH.png?resize=300%2C300&amp;ssl=1 300w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/2-CEK-SUHU-TUBUH.png?resize=150%2C150&amp;ssl=1 150w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/2-CEK-SUHU-TUBUH.png?resize=100%2C100&amp;ssl=1 100w"
                                                      sizes="(max-width: 150px) 100vw, 150px">
                                                    <figcaption class="widget-image-caption wp-caption-text">Cek Suhu
                                                      Tubuh</figcaption>
                                                  </figure>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div
                                            class="elementor-column elementor-col-25 elementor-inner-column elementor-element elementor-element-9ca43b1 wdp-sticky-section-no"
                                            data-id="9ca43b1" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-c6e4ccb wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                                                data-id="c6e4ccb" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="image.default">
                                                <div class="elementor-widget-container">
                                                  <figure class="wp-caption">
                                                    <img width="150" height="150"
                                                      src="https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/3-MENCUCI-TANGAN.png?fit=150%2C150&amp;ssl=1"
                                                      class="attachment-thumbnail size-thumbnail" alt="3 MENCUCI TANGAN"
                                                      loading="lazy"
                                                      srcset="https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/3-MENCUCI-TANGAN.png?w=535&amp;ssl=1 535w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/3-MENCUCI-TANGAN.png?resize=300%2C300&amp;ssl=1 300w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/3-MENCUCI-TANGAN.png?resize=150%2C150&amp;ssl=1 150w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/3-MENCUCI-TANGAN.png?resize=100%2C100&amp;ssl=1 100w"
                                                      sizes="(max-width: 150px) 100vw, 150px">
                                                    <figcaption class="widget-image-caption wp-caption-text">Mencuci
                                                      Tangan</figcaption>
                                                  </figure>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div
                                            class="elementor-column elementor-col-25 elementor-inner-column elementor-element elementor-element-2c0caae wdp-sticky-section-no"
                                            data-id="2c0caae" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-90215cf wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                                                data-id="90215cf" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                                data-widget_type="image.default">
                                                <div class="elementor-widget-container">
                                                  <figure class="wp-caption">
                                                    <img width="150" height="150"
                                                      src="https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/4-MENJAGA-JARAK.png?fit=150%2C150&amp;ssl=1"
                                                      class="attachment-thumbnail size-thumbnail" alt="4 MENJAGA JARAK"
                                                      loading="lazy"
                                                      srcset="https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/4-MENJAGA-JARAK.png?w=535&amp;ssl=1 535w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/4-MENJAGA-JARAK.png?resize=300%2C300&amp;ssl=1 300w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/4-MENJAGA-JARAK.png?resize=150%2C150&amp;ssl=1 150w, https://i0.wp.com/einvite.id/wp-content/uploads/2021/11/4-MENJAGA-JARAK.png?resize=100%2C100&amp;ssl=1 100w"
                                                      sizes="(max-width: 150px) 100vw, 150px">
                                                    <figcaption class="widget-image-caption wp-caption-text">Menjaga
                                                      Jarak</figcaption>
                                                  </figure>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </section>
                                    </div>
                                  </div>
                                </div>
                              </section> -->
                            </div>
                          </div>
                        </div>
                      </section>
                      <div class="elementor-element elementor-element-72ee02d elementor-absolute goyang-3 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="72ee02d" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="522" height="296" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?fit=522%2C296&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?w=522&amp;ssl=1 522w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?resize=150%2C85&amp;ssl=1 150w" sizes="(max-width: 522px) 100vw, 522px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-24d0919 elementor-absolute goyang-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="24d0919" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="469" height="586" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?fit=469%2C586&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?w=469&amp;ssl=1 469w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?resize=120%2C150&amp;ssl=1 120w" sizes="(max-width: 469px) 100vw, 469px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-574b4fa elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="574b4fa" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="386" height="351" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?fit=386%2C351&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?w=386&amp;ssl=1 386w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?resize=150%2C136&amp;ssl=1 150w" sizes="(max-width: 386px) 100vw, 386px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-e0d36d4 elementor-absolute goyang-3 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="e0d36d4" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-21,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="416" height="291" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?fit=416%2C291&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?w=416&amp;ssl=1 416w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?resize=150%2C105&amp;ssl=1 150w" sizes="(max-width: 416px) 100vw, 416px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-d976131 elementor-absolute goyang-1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="d976131" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="407" height="398" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?fit=407%2C398&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?w=407&amp;ssl=1 407w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?resize=150%2C147&amp;ssl=1 150w" sizes="(max-width: 407px) 100vw, 407px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-8381ea8 elementor-absolute goyang-1 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="8381ea8" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-13,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="316" height="295" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?fit=316%2C295&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?w=316&amp;ssl=1 316w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?resize=150%2C140&amp;ssl=1 150w" sizes="(max-width: 316px) 100vw, 316px">
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>

      <!-- Galery poto section  -->
      <section class="elementor-section elementor-top-section elementor-element elementor-element-80a9b41 elementor-section-height-min-height elementor-section-items-top hidden elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="80a9b41" data-element_type="section" id="gallery">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-184a9723 wdp-sticky-section-no" data-id="184a9723" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section class="elementor-section elementor-inner-section elementor-element elementor-element-6f85f2e0 elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="6f85f2e0" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <article data-dce-background-image-url="https://i2.wp.com/einvite.id/wp-content/uploads/kajian-bg-fix-02.jpg" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-490fb5c5 wdp-sticky-section-no" data-id="490fb5c5" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <section class="elementor-section elementor-inner-section elementor-element elementor-element-5d8557e2 elementor-section-full_width elementor-section-height-min-height elementor-section-height-default wdp-sticky-section-no" data-id="5d8557e2" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default">
                          <div data-dce-background-color="#FFFFFF4D" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-59e86bcf wdp-sticky-section-no" data-id="59e86bcf" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-61c55d4b wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="61c55d4b" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Galeri</h2>
                                </div>
                              </div>
                              <!-- <div
                                class="elementor-element elementor-element-076a6f8 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-html"
                                data-id="076a6f8" data-element_type="widget"
                                data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}"
                                data-widget_type="html.default">
                                <div class="elementor-widget-container">
                                  <video width="100%" poster="gambar/2.jpeg" controls id="videonya">
                                    <source src="https://www.youtube.com/watch?v=t1dvrcqlQgI" type="video/mp4">
                                    Your browser does not support the HTML5
                                  </video>
                                  <script type="rocketlazyloadscript">
                                    videonya.onplay = function pauseAudio (){
      document.getElementById("song").pause();
  };
  videonya.onpause = function playAudio(){
      document.getElementById("song").play();
  };
</script>
                                </div>
                              </div> -->
                              <!-- galery 1 -->
                              <!-- <div
                                class="elementor-element elementor-element-3607e22c wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-gallery"
                                data-id="3607e22c" data-element_type="widget"
                                data-settings="{&quot;columns_tablet&quot;:3,&quot;columns_mobile&quot;:3,&quot;gap&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:5,&quot;sizes&quot;:[]},&quot;gap_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:4,&quot;sizes&quot;:[]},&quot;gap_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:3,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;gallery_layout&quot;:&quot;grid&quot;,&quot;columns&quot;:4,&quot;link_to&quot;:&quot;file&quot;,&quot;aspect_ratio&quot;:&quot;3:2&quot;,&quot;overlay_background&quot;:&quot;yes&quot;,&quot;content_hover_animation&quot;:&quot;fade-in&quot;}"
                                data-widget_type="gallery.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-gallery__container">
                                    <a class="e-gallery-item elementor-gallery-item elementor-animated-content"
                                      data-toggle="lightbox" href="gambar/1.jpeg" data-elementor-open-lightbox="yes"
                                      data-elementor-lightbox-slideshow="all-3607e22c"
                                      e-action-hash="#elementor-action%3Aaction%3Dlightbox%26settings%3DeyJpZCI6MzMwODEsInVybCI6Imh0dHBzOlwvXC9laW52aXRlLmlkXC93cC1jb250ZW50XC91cGxvYWRzXC9VS1AwMTI3Ny53ZWJwIiwic2xpZGVzaG93IjoiYWxsLTM2MDdlMjJjIn0%3D">
                                      <div class="e-gallery-image elementor-gallery-item__image"
                                        data-thumbnail="gambar/1.jpeg" data-width="1024" data-height="683" alt></div>
                                      <div class="elementor-gallery-item__overlay"></div>
                                    </a>
                                    <a class="e-gallery-item elementor-gallery-item elementor-animated-content"
                                      href="gambar/3.jpeg" data-elementor-open-lightbox="yes"
                                      data-elementor-lightbox-slideshow="all-3607e22c"
                                      e-action-hash="#elementor-action%3Aaction%3Dlightbox%26settings%3DeyJpZCI6MzMwODIsInVybCI6Imh0dHBzOlwvXC9laW52aXRlLmlkXC93cC1jb250ZW50XC91cGxvYWRzXC9VMTE2MDk5OC53ZWJwIiwic2xpZGVzaG93IjoiYWxsLTM2MDdlMjJjIn0%3D">
                                      <div class="e-gallery-image elementor-gallery-item__image"
                                        data-thumbnail="gambar/3.jpeg" data-width="683" data-height="1024" alt></div>
                                      <div class="elementor-gallery-item__overlay"></div>
                                    </a>
                                    <a class="e-gallery-item elementor-gallery-item elementor-animated-content"
                                      href="gambar/2.jpeg" data-elementor-open-lightbox="yes"
                                      data-elementor-lightbox-slideshow="all-3607e22c"
                                      e-action-hash="#elementor-action%3Aaction%3Dlightbox%26settings%3DeyJpZCI6MzMwODMsInVybCI6Imh0dHBzOlwvXC9laW52aXRlLmlkXC93cC1jb250ZW50XC91cGxvYWRzXC9VMTE2MDc0My53ZWJwIiwic2xpZGVzaG93IjoiYWxsLTM2MDdlMjJjIn0%3D">
                                      <div class="e-gallery-image elementor-gallery-item__image"
                                        data-thumbnail="gambar/2.jpeg" data-width="1024" data-height="683" alt></div>
                                      <div class="elementor-gallery-item__overlay"></div>
                                    </a>
                                    <a class="e-gallery-item elementor-gallery-item elementor-animated-content"
                                      href="gambar/4.jpeg" data-elementor-open-lightbox="yes"
                                      data-elementor-lightbox-slideshow="all-3607e22c"
                                      e-action-hash="#elementor-action%3Aaction%3Dlightbox%26settings%3DeyJpZCI6MzMwODQsInVybCI6Imh0dHBzOlwvXC9laW52aXRlLmlkXC93cC1jb250ZW50XC91cGxvYWRzXC9VMTE2MDg1OS53ZWJwIiwic2xpZGVzaG93IjoiYWxsLTM2MDdlMjJjIn0%3D">
                                      <div class="e-gallery-image elementor-gallery-item__image"
                                        data-thumbnail="gambar/4.jpeg" data-width="683" data-height="1024" alt></div>
                                      <div class="elementor-gallery-item__overlay"></div>
                                    </a>
                                    <a class="e-gallery-item elementor-gallery-item elementor-animated-content"
                                      href="gambar/5.jpeg" data-elementor-open-lightbox="yes"
                                      data-elementor-lightbox-slideshow="all-3607e22c"
                                      e-action-hash="#elementor-action%3Aaction%3Dlightbox%26settings%3DeyJpZCI6MzMwODUsInVybCI6Imh0dHBzOlwvXC9laW52aXRlLmlkXC93cC1jb250ZW50XC91cGxvYWRzXC9VMTE2MDcwOS53ZWJwIiwic2xpZGVzaG93IjoiYWxsLTM2MDdlMjJjIn0%3D">
                                      <div class="e-gallery-image elementor-gallery-item__image"
                                        data-thumbnail="gambar/5.jpeg" data-width="1024" data-height="683" alt></div>
                                      <div class="elementor-gallery-item__overlay"></div>
                                    </a>
                                    <a class="e-gallery-item elementor-gallery-item elementor-animated-content"
                                      href="gambar/6.jpeg" data-elementor-open-lightbox="yes"
                                      data-elementor-lightbox-slideshow="all-3607e22c"
                                      e-action-hash="#elementor-action%3Aaction%3Dlightbox%26settings%3DeyJpZCI6MzMwODYsInVybCI6Imh0dHBzOlwvXC9laW52aXRlLmlkXC93cC1jb250ZW50XC91cGxvYWRzXC9VMTE2MDc5NC53ZWJwIiwic2xpZGVzaG93IjoiYWxsLTM2MDdlMjJjIn0%3D">
                                      <div class="e-gallery-image elementor-gallery-item__image"
                                        data-thumbnail="gambar/6.jpeg" data-width="683" data-height="1024" alt></div>
                                      <div class="elementor-gallery-item__overlay"></div>
                                    </a>

                                  </div>
                                </div>
                              </div> -->

                              <div class="elementor-column-wrap elementor-element-populated">
                                <div class="elementor-widget-wrap">
                                  <div class="elementor-element elementor-element-5e02e4cb elementor-widget elementor-widget-image-gallery" data-id="5e02e4cb" data-element_type="widget" data-widget_type="image-gallery.default">
                                    <div class="elementor-widget-container">
                                      <div class="elementor-image-gallery">
                                        <div id="gallery-1" class="gallery galleryid-2031 gallery-columns-1 gallery-size-large">
                                          <figure class="gallery-item">
                                            <div class="gallery-icon landscape">
                                              <a data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="5e02e4cb" data-elementor-lightbox-title="1.jpeg" href="gambar/1.jpeg"><img width="1024" height="683" src="gambar/1.jpeg" class="attachment-large size-large" alt="" loading="lazy"></a>
                                            </div>
                                          </figure>
                                          <figure class="gallery-item">
                                            <div class="gallery-icon landscape">
                                              <a data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="5e02e4cb" data-elementor-lightbox-title="galery2.jpeg" href="gambar/2.jpeg"><img width="1024" height="683" src="gambar/2.jpeg" class="attachment-large size-large" alt="" loading="lazy"></a>
                                            </div>
                                          </figure>
                                          <figure class="gallery-item">
                                            <div class="gallery-icon landscape">
                                              <a data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="5e02e4cb" data-elementor-lightbox-title="galery3.jpeg" href="gambar/3.jpeg"><img width="1024" height="683" src="gambar/3.jpeg" class="attachment-large size-large" alt="" loading="lazy"></a>
                                            </div>
                                          </figure>
                                          <figure class="gallery-item">
                                            <div class="gallery-icon landscape">
                                              <a data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="5e02e4cb" data-elementor-lightbox-title="galery4.jpeg" href="gambar/4.jpeg"><img width="1024" height="683" src="gambar/4.jpeg" class="attachment-large size-large" alt="" loading="lazy"></a>
                                            </div>
                                          </figure>
                                          <figure class="gallery-item">
                                            <div class="gallery-icon landscape">
                                              <a data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="5e02e4cb" data-elementor-lightbox-title="galery5.jpeg" href="gambar/5.jpeg"><img width="1024" height="683" src="gambar/5.jpeg" class="attachment-large size-large" alt="" loading="lazy"></a>
                                            </div>
                                          </figure>
                                          <figure class="gallery-item">
                                          </figure>
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </section>
                      <div class="elementor-element elementor-element-7d88c3d elementor-absolute goyang-3 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="7d88c3d" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="522" height="296" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?fit=522%2C296&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?w=522&amp;ssl=1 522w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?resize=150%2C85&amp;ssl=1 150w" sizes="(max-width: 522px) 100vw, 522px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-75d452d elementor-absolute goyang-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="75d452d" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="469" height="586" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?fit=469%2C586&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?w=469&amp;ssl=1 469w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?resize=120%2C150&amp;ssl=1 120w" sizes="(max-width: 469px) 100vw, 469px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-5571089 elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="5571089" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="386" height="351" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?fit=386%2C351&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?w=386&amp;ssl=1 386w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?resize=150%2C136&amp;ssl=1 150w" sizes="(max-width: 386px) 100vw, 386px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-77c8c74 elementor-absolute goyang-3 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="77c8c74" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-21,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="416" height="291" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?fit=416%2C291&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?w=416&amp;ssl=1 416w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?resize=150%2C105&amp;ssl=1 150w" sizes="(max-width: 416px) 100vw, 416px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-bbf36c7 elementor-absolute goyang-1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="bbf36c7" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="407" height="398" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?fit=407%2C398&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?w=407&amp;ssl=1 407w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?resize=150%2C147&amp;ssl=1 150w" sizes="(max-width: 407px) 100vw, 407px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-34719c0 elementor-absolute goyang-1 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="34719c0" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-13,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="316" height="295" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?fit=316%2C295&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?w=316&amp;ssl=1 316w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?resize=150%2C140&amp;ssl=1 150w" sizes="(max-width: 316px) 100vw, 316px">
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>
      <!-- <section
        class="elementor-section elementor-top-section elementor-element elementor-element-1aebda5 elementor-section-height-min-height elementor-section-items-top hidden elementor-section-boxed elementor-section-height-default wdp-sticky-section-no"
        data-id="1aebda5" data-element_type="section" id="konfirmasi">
        <div class="elementor-container elementor-column-gap-default">
          <div
            class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-1c14521 wdp-sticky-section-no"
            data-id="1c14521" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section
                class="elementor-section elementor-inner-section elementor-element elementor-element-cef3559 elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wdp-sticky-section-no"
                data-id="cef3559" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <article
                    data-dce-background-image-url="https://i2.wp.com/einvite.id/wp-content/uploads/kajian-bg-fix-01.jpg"
                    class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-818ce2a wdp-sticky-section-no"
                    data-id="818ce2a" data-element_type="column"
                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <section
                        class="elementor-section elementor-inner-section elementor-element elementor-element-7824346 elementor-section-full_width elementor-section-height-min-height elementor-section-height-default wdp-sticky-section-no"
                        data-id="7824346" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default">
                          <div data-dce-background-color="#FFFFFF63"
                            class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-aad2c4a wdp-sticky-section-no"
                            data-id="aad2c4a" data-element_type="column"
                            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <section
                                class="elementor-section elementor-inner-section elementor-element elementor-element-c4912a7 elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
                                data-id="c4912a7" data-element_type="section">
                                <div class="elementor-container elementor-column-gap-default">
                                  <div data-dce-background-color="#FFFFFF7D"
                                    class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-f16274c wdp-sticky-section-no"
                                    data-id="f16274c" data-element_type="column"
                                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                      <div
                                        class="elementor-element elementor-element-da0c3f8 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="da0c3f8" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Konfirmasi
                                            Kehadiran</h2>
                                        </div>
                                      </div>
                                      <div
                                        data-pafe-form-google-sheets-connector="1zzodOSS_LDiqU6WDUqPxJSoQhvaJLtaGRnVQAGElpR8"
                                        data-pafe-form-google-sheets-connector-clid="992986407523-pq61e4mjtqj3iieu1ka5tamrti2vj2e1.apps.googleusercontent.com"
                                        data-pafe-form-google-sheets-connector-clis="prv1SkT3sKyPSofNBpLNprvg"
                                        data-pafe-form-google-sheets-connector-rtok="1//0gG8YZk0temVHCgYIARAAGBASNwF-L9IrlgTWv_n8NepYM5liwpcWLEHqCtzfGwR47lNAw_WDdZ-YC9Q4C4xR6GjwkqH92H3MLbE"
                                        data-pafe-form-google-sheets-connector-field-list="[{&quot;_id&quot;:&quot;e933544&quot;,&quot;pafe_form_google_sheets_connector_field_id&quot;:&quot;nama&quot;,&quot;pafe_form_google_sheets_connector_field_column&quot;:&quot;A&quot;},{&quot;_id&quot;:&quot;65b569e&quot;,&quot;pafe_form_google_sheets_connector_field_id&quot;:&quot;handphone&quot;,&quot;pafe_form_google_sheets_connector_field_column&quot;:&quot;B&quot;},{&quot;pafe_form_google_sheets_connector_field_id&quot;:&quot;alamat&quot;,&quot;pafe_form_google_sheets_connector_field_column&quot;:&quot;C&quot;,&quot;_id&quot;:&quot;622b750&quot;},{&quot;_id&quot;:&quot;5a098d4&quot;,&quot;pafe_form_google_sheets_connector_field_id&quot;:&quot;kehadiran&quot;,&quot;pafe_form_google_sheets_connector_field_column&quot;:&quot;D&quot;},{&quot;pafe_form_google_sheets_connector_field_id&quot;:&quot;partner&quot;,&quot;pafe_form_google_sheets_connector_field_column&quot;:&quot;E&quot;,&quot;_id&quot;:&quot;e5ed9a0&quot;},{&quot;_id&quot;:&quot;547610d&quot;,&quot;pafe_form_google_sheets_connector_field_id&quot;:&quot;jumlah&quot;,&quot;pafe_form_google_sheets_connector_field_column&quot;:&quot;F&quot;}]"
                                        data-pafe-form-google-sheets-connector-tab
                                        class="elementor-element elementor-element-971920c elementor-button-align-center wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-form"
                                        data-id="971920c" data-element_type="widget"
                                        data-settings="{&quot;step_next_label&quot;:&quot;Next&quot;,&quot;step_previous_label&quot;:&quot;Previous&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;button_width&quot;:&quot;100&quot;,&quot;step_type&quot;:&quot;number_text&quot;,&quot;step_icon_shape&quot;:&quot;circle&quot;}"
                                        data-widget_type="form.default">
                                        <div class="elementor-widget-container">
                                          <form class="elementor-form" method="post" id="formcondiandrifitria"
                                            name="Form Kehadiran Tema">
                                            <input type="hidden" name="post_id" value="64448" />
                                            <input type="hidden" name="form_id" value="971920c" />
                                            <input type="hidden" name="referer_title"
                                              value="Premium 17 - Green White Gold Roses - einvite.id" />
                                            <input type="hidden" name="queried_id" value="64448" />
                                            <div class="elementor-form-fields-wrapper elementor-labels-">
                                              <div
                                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-nama elementor-col-50">
                                                <label for="form-field-nama"
                                                  class="elementor-field-label elementor-screen-only">
                                                  Nama </label>
                                                <input size="1" type="text" name="form_fields[nama]"
                                                  id="form-field-nama"
                                                  class="elementor-field elementor-size-sm  elementor-field-textual"
                                                  placeholder="Nama">
                                              </div>
                                              <div
                                                class="elementor-field-type-number elementor-field-group elementor-column elementor-field-group-handphone elementor-col-50 elementor-field-required">
                                                <label for="form-field-handphone"
                                                  class="elementor-field-label elementor-screen-only">
                                                  Handphone </label>
                                                <input type="number" name="form_fields[handphone]"
                                                  id="form-field-handphone"
                                                  class="elementor-field elementor-size-sm  elementor-field-textual"
                                                  placeholder="Handphone" required="required" aria-required="true" min
                                                  max>
                                              </div>
                                              <div
                                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-alamat elementor-col-100 elementor-field-required">
                                                <label for="form-field-alamat"
                                                  class="elementor-field-label elementor-screen-only">
                                                  Alamat Lengkap </label>
                                                <input size="1" type="text" name="form_fields[alamat]"
                                                  id="form-field-alamat"
                                                  class="elementor-field elementor-size-sm  elementor-field-textual"
                                                  placeholder="Alamat" required="required" aria-required="true">
                                              </div>
                                              <div
                                                class="elementor-field-type-html elementor-field-group elementor-column elementor-field-group-kehadiran elementor-col-100">
                                                <b>Apakah anda dapat hadir?</b> </div>
                                              <div
                                                class="elementor-field-type-select elementor-field-group elementor-column elementor-field-group-kehadiran elementor-col-50">
                                                <label for="form-field-kehadiran"
                                                  class="elementor-field-label elementor-screen-only">
                                                  Konfirmasi Kehadiran </label>
                                                <div class="elementor-field elementor-select-wrapper ">
                                                  <select name="form_fields[kehadiran]" id="form-field-kehadiran"
                                                    class="elementor-field-textual elementor-size-sm">
                                                    <option value="Saya Akan Hadir">Saya Akan Hadir</option>
                                                    <option value="Maaf, Saya Belum Dapat Hadir">Maaf, Saya Belum Dapat
                                                      Hadir</option>
                                                  </select>
                                                </div>
                                              </div>
                                              <div
                                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-partner elementor-col-50">
                                                <label for="form-field-partner"
                                                  class="elementor-field-label elementor-screen-only">
                                                  Partner </label>
                                                <input size="1" type="text" name="form_fields[partner]"
                                                  id="form-field-partner"
                                                  class="elementor-field elementor-size-sm  elementor-field-textual"
                                                  placeholder="Tuliskan nama pasangan/partner anda">
                                              </div>
                                              <div
                                                class="elementor-field-type-html elementor-field-group elementor-column elementor-field-group-jumlah elementor-col-100">
                                                <b>Jumlah Tamu</b> </div>
                                              <div
                                                class="elementor-field-type-select elementor-field-group elementor-column elementor-field-group-jumlah elementor-col-50">
                                                <label for="form-field-jumlah"
                                                  class="elementor-field-label elementor-screen-only">
                                                  Jumlah </label>
                                                <div class="elementor-field elementor-select-wrapper ">
                                                  <select name="form_fields[jumlah]" id="form-field-jumlah"
                                                    class="elementor-field-textual elementor-size-sm">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                  </select>
                                                </div>
                                              </div>
                                              <div
                                                class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
                                                <button type="submit"
                                                  class="elementor-button elementor-size-sm elementor-animation-grow">
                                                  <span>
                                                    <span class=" elementor-button-icon">
                                                    </span>
                                                    <span class="elementor-button-text">Kirimkan Konfirmasi</span>
                                                  </span>
                                                </button>
                                              </div>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                      <div data-dce-background-color="#263757"
                                        class="elementor-element elementor-element-a66f6d7 elementor-align-center wdp-sticky-section-no elementor-widget elementor-widget-button"
                                        data-id="a66f6d7" data-element_type="widget" data-widget_type="button.default">
                                        <div class="elementor-widget-container">
                                          <div class="elementor-button-wrapper">
                                            <a href="https://docs.google.com/spreadsheets/d/1zzodOSS_LDiqU6WDUqPxJSoQhvaJLtaGRnVQAGElpR8"
                                              class="elementor-button-link elementor-button elementor-size-xs"
                                              role="button">
                                              <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-icon elementor-align-icon-left">
                                                  <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px"
                                                    y="0px" viewbox="0 0 407.524 407.524"
                                                    style="enable-background:new 0 0 407.524 407.524;"
                                                    xml:space="preserve">
                                                    <g>
                                                      <g>
                                                        <path
                                                          d="M336.124,74.854h-72.83V7.5c0-2.494-1.24-4.826-3.311-6.221c-2.068-1.395-4.695-1.668-7.01-0.729L69.007,74.854    c-1.33,0.539-2.498,1.52-3.381,2.713c-1.078,1.299-1.727,2.967-1.727,4.787v317.67c0,4.143,3.357,7.5,7.5,7.5h264.725    c4.143,0,7.5-3.357,7.5-7.5V82.354C343.624,78.211,340.267,74.854,336.124,74.854z M248.294,18.639v56.215H109.816L248.294,18.639    z M328.624,392.524H78.9V89.854h249.725V392.524z">
                                                        </path>
                                                        <path
                                                          d="M152.089,230.424c-3.516-8.709-3.385-17.281,0.367-24.137c2.988-5.465,8.109-9.354,13.695-10.402    c4.072-0.766,6.752-4.688,5.986-8.758c-0.768-4.07-4.689-6.748-8.758-5.984c-10.105,1.9-18.883,8.439-24.084,17.943    c-5.949,10.873-6.346,23.996-1.117,36.951c1.18,2.922,3.99,4.695,6.959,4.695c0.934,0,1.885-0.176,2.803-0.547    C151.782,238.635,153.64,234.264,152.089,230.424z">
                                                        </path>
                                                        <path
                                                          d="M200.257,326.01c1.098,0.582,2.303,0.871,3.508,0.871c1.207,0,2.412-0.289,3.51-0.871    c36.41-19.273,62.59-41.086,77.81-64.83c16.643-25.967,16.094-47.691,12.703-61.344c-6.381-25.691-28.902-44.34-53.551-44.34    c-15.457,0-29.529,7.018-40.473,19.982c-10.943-12.965-25.016-19.982-40.473-19.982c-24.652,0-47.176,18.648-53.557,44.342    c-3.391,13.652-3.938,35.375,12.707,61.34C137.663,284.922,163.843,306.735,200.257,326.01z M124.294,203.454    c4.742-19.098,21.145-32.957,38.998-32.957c13.279,0,25.393,7.705,34.107,21.697c1.369,2.199,3.775,3.535,6.365,3.535    c2.59,0,4.998-1.336,6.367-3.535c8.715-13.992,20.828-21.697,34.105-21.697c17.852,0,34.25,13.859,38.992,32.955    c3.322,13.379,7.23,60.246-79.465,107.418C117.066,263.696,120.972,216.831,124.294,203.454z">
                                                        </path>
                                                      </g>
                                                    </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                  </svg> </span>
                                                <span class="elementor-button-text">Contoh RSVP</span>
                                              </span>
                                            </a>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </section>
                              <section
                                class="elementor-section elementor-inner-section elementor-element elementor-element-d324b74 elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
                                data-id="d324b74" data-element_type="section">
                                <div class="elementor-container elementor-column-gap-default">
                                  <div data-dce-background-color="#FFFFFF7D"
                                    class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-5c0582d wdp-sticky-section-no"
                                    data-id="5c0582d" data-element_type="column"
                                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                      <div
                                        class="elementor-element elementor-element-9f6b8a2 naik-turun elementor-view-default wdp-sticky-section-no elementor-widget elementor-widget-icon"
                                        data-id="9f6b8a2" data-element_type="widget" data-widget_type="icon.default">
                                        <div class="elementor-widget-container">
                                          <div class="elementor-icon-wrapper">
                                            <div class="elementor-icon">
                                              <i aria-hidden="true" class="fas fa-gifts"></i> </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div
                                        class="elementor-element elementor-element-0cf7e79 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading"
                                        data-id="0cf7e79" data-element_type="widget"
                                        data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}"
                                        data-widget_type="heading.default">
                                        <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Kirim Hadiah</h2>
                                        </div>
                                      </div>
                                      <section
                                        class="elementor-section elementor-inner-section elementor-element elementor-element-e77ab2e elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
                                        data-id="e77ab2e" data-element_type="section">
                                        <div class="elementor-container elementor-column-gap-default">
                                          <div
                                            class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-6e284b5 wdp-sticky-section-no"
                                            data-id="6e284b5" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-9b84573 elementor-widget-divider--view-line wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-divider"
                                                data-id="9b84573" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;fadeInLeft&quot;}"
                                                data-widget_type="divider.default">
                                                <div class="elementor-widget-container">
                                                  <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                  </div>
                                                </div>
                                              </div>
                                              <div data-dce-background-color="#477D59"
                                                class="elementor-element elementor-element-aea9b8b elementor-align-center wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-weddingpress-copy-text"
                                                data-id="aea9b8b" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                                data-widget_type="weddingpress-copy-text.default">
                                                <div class="elementor-widget-container">
                                                  <div class="elementor-image img"><img
                                                      src="https://i0.wp.com/einvite.id/wp-content/uploads/mandiri.webp?w=800&#038;ssl=1"
                                                      title="Mandiri" alt="mandiri" data-recalc-dims="1"></div>
                                                  <div class="head-title">a.n Julian dari Einvite.id</div>
                                                  <div class="elementor-button-wrapper">
                                                    <div class="copy-content spancontent">031 000 123 45xx</div>
                                                    <a style="cursor:pointer;" onclick="copyText(this)"
                                                      data-message="Berhasil disalin" class="elementor-button"
                                                      role="button">
                                                      <div class="elementor-button-content-wrapper">
                                                        <span class="elementor-button-icon elementor-align-icon-left">
                                                          <i aria-hidden="true" class="far fa-copy"></i> </span>
                                                        <span class="elementor-button-text">Copy</span>
                                                      </div>
                                                    </a>
                                                  </div>
                                                  <style type="text/css">
                                                    .spancontent {
                                                      padding-bottom: 20px;
                                                    }

                                                    .copy-content {
                                                      color: #6EC1E4;
                                                      text-align: center;
                                                    }

                                                    .head-title {
                                                      color: #6EC1E4;
                                                      text-align: center;
                                                    }
                                                  </style>
                                                  <script type="rocketlazyloadscript">
                                                    function copyText(el) {
		    var content = jQuery(el).siblings('div.copy-content').html()
		    var temp = jQuery("<textarea>");
		    jQuery("body").append(temp);
		    temp.val(content.replace(/<br ?\/?>/g, "\n")).select();
		    document.execCommand("copy");
		    temp.remove();
		    var text = jQuery(el).html()
		    jQuery(el).html(jQuery(el).data('message'))
		    var counter = 0;
		    var interval = setInterval(function() {
		        counter++;
		        if (counter == 1) {
		            jQuery(el).html(text)
		        }
		    }, 500);
		}

		</script>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div
                                            class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-c0bfb68 wdp-sticky-section-no"
                                            data-id="c0bfb68" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                              <div
                                                class="elementor-element elementor-element-bdc6e3c elementor-widget-divider--view-line wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-divider"
                                                data-id="bdc6e3c" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;fadeInRight&quot;}"
                                                data-widget_type="divider.default">
                                                <div class="elementor-widget-container">
                                                  <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                  </div>
                                                </div>
                                              </div>
                                              <div data-dce-background-color="#477D59"
                                                class="elementor-element elementor-element-8aa2fde elementor-align-center wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-weddingpress-copy-text"
                                                data-id="8aa2fde" data-element_type="widget"
                                                data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                                data-widget_type="weddingpress-copy-text.default">
                                                <div class="elementor-widget-container">
                                                  <div class="elementor-image img"><img
                                                      src="https://i0.wp.com/einvite.id/wp-content/uploads/logo-bca.webp?w=800&#038;ssl=1"
                                                      title="Logo-Bca" alt="logo-bca" data-recalc-dims="1"></div>
                                                  <div class="head-title">a.n Julian dari Einvite.id</div>
                                                  <div class="elementor-button-wrapper">
                                                    <div class="copy-content spancontent">031 000 123 45xx</div>
                                                    <a style="cursor:pointer;" onclick="copyText(this)"
                                                      data-message="Berhasil disalin" class="elementor-button"
                                                      role="button">
                                                      <div class="elementor-button-content-wrapper">
                                                        <span class="elementor-button-icon elementor-align-icon-left">
                                                          <i aria-hidden="true" class="far fa-copy"></i> </span>
                                                        <span class="elementor-button-text">Copy</span>
                                                      </div>
                                                    </a>
                                                  </div>
                                                  <style type="text/css">
                                                    .spancontent {
                                                      padding-bottom: 20px;
                                                    }

                                                    .copy-content {
                                                      color: #6EC1E4;
                                                      text-align: center;
                                                    }

                                                    .head-title {
                                                      color: #6EC1E4;
                                                      text-align: center;
                                                    }
                                                  </style>
                                                  <script type="rocketlazyloadscript">
                                                    function copyText(el) {
		    var content = jQuery(el).siblings('div.copy-content').html()
		    var temp = jQuery("<textarea>");
		    jQuery("body").append(temp);
		    temp.val(content.replace(/<br ?\/?>/g, "\n")).select();
		    document.execCommand("copy");
		    temp.remove();
		    var text = jQuery(el).html()
		    jQuery(el).html(jQuery(el).data('message'))
		    var counter = 0;
		    var interval = setInterval(function() {
		        counter++;
		        if (counter == 1) {
		            jQuery(el).html(text)
		        }
		    }, 500);
		}

		</script>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </section>
                                    </div>
                                  </div>
                                </div>
                              </section>
                            </div>
                          </div>
                        </div>
                      </section>
                      <div
                        class="elementor-element elementor-element-a40f107 elementor-absolute goyang-3 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                        data-id="a40f107" data-element_type="widget"
                        data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;}"
                        data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="522" height="296"
                            src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?fit=522%2C296&amp;ssl=1"
                            class="attachment-large size-large" alt="kajian bunga kanan atas 03" loading="lazy"
                            srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?w=522&amp;ssl=1 522w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?resize=150%2C85&amp;ssl=1 150w"
                            sizes="(max-width: 522px) 100vw, 522px"> </div>
                      </div>
                      <div
                        class="elementor-element elementor-element-bc5c03c elementor-absolute goyang-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                        data-id="bc5c03c" data-element_type="widget"
                        data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}"
                        data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="469" height="586"
                            src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?fit=469%2C586&amp;ssl=1"
                            class="attachment-large size-large" alt="kajian bunga kanan atas 02" loading="lazy"
                            srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?w=469&amp;ssl=1 469w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?resize=120%2C150&amp;ssl=1 120w"
                            sizes="(max-width: 469px) 100vw, 469px"> </div>
                      </div>
                      <div
                        class="elementor-element elementor-element-b742aea elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                        data-id="b742aea" data-element_type="widget"
                        data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}"
                        data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="386" height="351"
                            src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?fit=386%2C351&amp;ssl=1"
                            class="attachment-large size-large" alt="kajian bunga kanan atas 01" loading="lazy"
                            srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?w=386&amp;ssl=1 386w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?resize=150%2C136&amp;ssl=1 150w"
                            sizes="(max-width: 386px) 100vw, 386px"> </div>
                      </div>
                      <div
                        class="elementor-element elementor-element-3691a2e elementor-absolute goyang-3 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                        data-id="3691a2e" data-element_type="widget"
                        data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-21,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}"
                        data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="416" height="291"
                            src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?fit=416%2C291&amp;ssl=1"
                            class="attachment-large size-large" alt="kajian bunga kiri bawah 03" loading="lazy"
                            srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?w=416&amp;ssl=1 416w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?resize=150%2C105&amp;ssl=1 150w"
                            sizes="(max-width: 416px) 100vw, 416px"> </div>
                      </div>
                      <div
                        class="elementor-element elementor-element-ca5bc0a elementor-absolute goyang-1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                        data-id="ca5bc0a" data-element_type="widget"
                        data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}"
                        data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="407" height="398"
                            src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?fit=407%2C398&amp;ssl=1"
                            class="attachment-large size-large" alt="kajian bunga kiri bawah 02" loading="lazy"
                            srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?w=407&amp;ssl=1 407w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?resize=150%2C147&amp;ssl=1 150w"
                            sizes="(max-width: 407px) 100vw, 407px"> </div>
                      </div>
                      <div
                        class="elementor-element elementor-element-e3aec57 elementor-absolute goyang-1 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image"
                        data-id="e3aec57" data-element_type="widget"
                        data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-13,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}"
                        data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="316" height="295"
                            src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?fit=316%2C295&amp;ssl=1"
                            class="attachment-large size-large" alt="kajian bunga kiri bawah 01" loading="lazy"
                            srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?w=316&amp;ssl=1 316w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?resize=150%2C140&amp;ssl=1 150w"
                            sizes="(max-width: 316px) 100vw, 316px"> </div>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section> -->

      <!-- kirim ucapan dana doa  -->
      <section class="elementor-section elementor-top-section elementor-element elementor-element-31a476c4 elementor-section-height-min-height elementor-section-items-top hidden elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="31a476c4" data-element_type="section" id="doa">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-cddf017 wdp-sticky-section-no" data-id="cddf017" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section class="elementor-section elementor-inner-section elementor-element elementor-element-403d7f0e elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="403d7f0e" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <article data-dce-background-image-url="https://i2.wp.com/einvite.id/wp-content/uploads/kajian-bg-fix-01.jpg" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-53bc7032 wdp-sticky-section-no" data-id="53bc7032" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <section class="elementor-section elementor-inner-section elementor-element elementor-element-54a67c6e elementor-section-full_width elementor-section-height-min-height elementor-section-height-default wdp-sticky-section-no" data-id="54a67c6e" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default">
                          <div data-dce-background-color="#FFFFFF4D" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-1aa42681 wdp-sticky-section-no" data-id="1aa42681" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-6526fa28 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="6526fa28" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Ucapan & Doa</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-a448920 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="a448920" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Berikan ucapan terbaik
                                    untuk kedua mempelai</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-75e00fb wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-weddingpress-commentkit" data-id="75e00fb" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="weddingpress-commentkit.default">
                                <div class="elementor-widget-container">
                                  <div class="wdp-wrapper wdp-golden wdp-border" style="overflow: hidden;">

                                  <?php 
                                  include 'koneksi.php';
                                  $sql = "SELECT * FROM komen";
                                  $res = $conn->query($sql);
                                  $totalwis = mysqli_num_rows($res);
                                  ?>
                                    <div class="wdp-wrap-link"><a id="wdp-link-64448" class="wdp-link wdp-icon-link wdp-icon-link-true auto-load-true" href="?post_id=64448&amp;comments=4&amp;get=0&amp;order=DESC" title="4 Wishes"><i aria-hidden="true" class="fas fa-dove"> </i> <span><?php echo $totalwis; ?></span>
                                        Wishes</a></div>
                                    <div id="wdp-wrap-commnent-64448" class="wdp-wrap-comments" style="display:none;">
                                      <div id="wdp-wrap-form-64448" class="wdp-wrap-form wdp-clearfix">
                                        <div id="wdp-container-form-64448" class="wdp-container-form wdp-no-login">
                                          <div id="respond-64448" class="respond wdp-clearfix">
                                            <form action="post.php" method="post" id="commentform-64448">
                                              <p class="comment-form-author wdp-field-1">
                                                <input id="author" name="author" type="text" aria-required="true" class="wdp-input" placeholder="Nama Anda" /><span class="wdp-required">*</span><span class="wdp-error-info wdp-error-info-name">Mohon maaf! Khusus untuk
                                                  tamu undangan</span>
                                              </p>
                                              <div class="wdp-wrap-textarea"><textarea id="wdp-textarea-64448" class="waci_comment wdp-textarea autosize-textarea" name="comment" aria-required="true" placeholder="Tulis Ucapan & Doa" rows="3"></textarea><span class="wdp-required">*</span><span class="wdp-error-info wdp-error-info-text">Minimal 2 karakter.</span>
                                              </div>

                                              <?php 
                                              // $date = date("Y-m-d");
                                              // $time = date("H:m");
                                              // $datetime = $date . "T" . $time; 
                                              $date = new DateTime("now", new DateTimeZone('America/New_York') );
                                              
                                              ?>
                                              <input type="hidden" name="date" value="<?php echo $date->format('Y-m-d H:i:s'); ?>">
                                              <!-- navar konfirmasi beri hadiah  -->
                                              <div class="wdp-wrap-select"><select class="waci_comment wdp-select" name="konfirmasi">
                                                  <option value disabled selected>Konfirmasi Kehadiran</option>
                                                  <option value="Hadir">Hadir</option>
                                                  <option value="Tidak hadir">Tidak hadir</option>
                                                </select><span class="wdp-required">*</span><span class="wdp-error-info wdp-error-info-confirm">Silahkan pilih
                                                  konfirmasi kehadiran</span></div>
                                              <div class="wdp-wrap-submit wdp-clearfix">
                                                <p class="form-submit"><span class="wdp-hide">Do not change these fields
                                                    following</span><input type="text" class="wdp-hide" name="name" value="username"><input type="text" class="wdp-hide" name="nombre" value><input type="text" class="wdp-hide" name="form-wdp" value><input type="button" class="wdp-form-btn wdp-cancel-btn" value="Batal">

                                                  <!-- submit  -->
                                                  <input name="submit" id="submit-64448" value="Kirim" type="submit" onclick="foo()" />

                                                  <input type="hidden" name="commentpress" value="true" /><input type="hidden" name="comment_post_ID" value="64448" id="comment_post_ID" />
                                                  <input type="hidden" name="comment_parent" id="comment_parent" value="0" />
                                                </p>
                                              </div>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                      <div id="wdp-comment-status-64448" class="wdp-comment-status"></div>
                                      <ul id="wdp-container-comment-64448" class="wdp-container-comments wdp-order-DESC  wdp-has-4-comments wdp-multiple-comments" data-order="DESC"></ul>
                                      <div class="wdp-holder-64448 wdp-holder"></div>

                                      <!-- komen seksi cuy  -->
                                      <style>
                                        #komenseksi {
                                          overflow: auto;
                                          height: 400px;
                                        }
                                      </style>
                                      <div id="komenseksi">
                                        
                                        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                        <?php include 'getdata.php'; ?>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>
                      <div class="elementor-element elementor-element-8997af3 elementor-absolute goyang-3 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="8997af3" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="522" height="296" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?fit=522%2C296&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?w=522&amp;ssl=1 522w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?resize=150%2C85&amp;ssl=1 150w" sizes="(max-width: 522px) 100vw, 522px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-6a4c266 elementor-absolute goyang-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="6a4c266" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="469" height="586" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?fit=469%2C586&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?w=469&amp;ssl=1 469w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?resize=120%2C150&amp;ssl=1 120w" sizes="(max-width: 469px) 100vw, 469px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-183c927 elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="183c927" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="386" height="351" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?fit=386%2C351&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?w=386&amp;ssl=1 386w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?resize=150%2C136&amp;ssl=1 150w" sizes="(max-width: 386px) 100vw, 386px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-02bfd70 elementor-absolute goyang-3 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="02bfd70" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-21,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="416" height="291" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?fit=416%2C291&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?w=416&amp;ssl=1 416w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?resize=150%2C105&amp;ssl=1 150w" sizes="(max-width: 416px) 100vw, 416px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-3fc56dc elementor-absolute goyang-1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="3fc56dc" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="407" height="398" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?fit=407%2C398&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?w=407&amp;ssl=1 407w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?resize=150%2C147&amp;ssl=1 150w" sizes="(max-width: 407px) 100vw, 407px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-183c661 elementor-absolute goyang-1 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="183c661" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-13,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="316" height="295" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?fit=316%2C295&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?w=316&amp;ssl=1 316w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?resize=150%2C140&amp;ssl=1 150w" sizes="(max-width: 316px) 100vw, 316px">
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>

      <!-- footer  -->
      <section class="elementor-section elementor-top-section elementor-element elementor-element-888bfcd elementor-section-height-min-height elementor-section-items-top hidden elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="888bfcd" data-element_type="section" id="closing">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-7fab137d wdp-sticky-section-no" data-id="7fab137d" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section class="elementor-section elementor-inner-section elementor-element elementor-element-323552bc elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wdp-sticky-section-no" data-id="323552bc" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <article data-dce-background-image-url="https://i2.wp.com/einvite.id/wp-content/uploads/kajian-bg-fix-02.jpg" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-5bf8b7c1 wdp-sticky-section-no" data-id="5bf8b7c1" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <section class="elementor-section elementor-inner-section elementor-element elementor-element-7b03aaca elementor-section-full_width elementor-section-height-min-height elementor-section-height-default wdp-sticky-section-no" data-id="7b03aaca" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default">
                          <div data-dce-background-color="#FFFFFF4D" class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-45b3de6a wdp-sticky-section-no" data-id="45b3de6a" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-54ea9d60 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="54ea9d60" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Atas kehadiran dan do’a
                                    restu dari Bapak/Ibu/Saudara/i sekalian, kami mengucapkan Terima Kasih.</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-7631c606 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="7631c606" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Wassalamu’alaikum Wr. Wb.
                                  </h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-3a07c817 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="3a07c817" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                                <div class="elementor-widget-container">
                                  <img width="1024" height="683" src="gambar/Cover-Photo.webp" class="attachment-large size-large" alt="RIF08601" loading="lazy" srcset="gambar/Cover-Photo.webp 1024w, gambar/Cover-Photo.webp 150w, gambar/Cover-Photo.webp 768w, gambar/Cover-Photo.webp 600w" sizes="(max-width: 800px) 100vw, 800px">
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-7f53650b wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="7f53650b" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Kami yang berbahagia</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-344e6500 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="344e6500" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">Khairil & Riska</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-ea84895 elementor-widget-divider--view-line_icon elementor-view-default elementor-widget-divider--element-align-center wdp-sticky-section-no elementor-widget elementor-widget-divider" data-id="ea84895" data-element_type="widget" data-widget_type="divider.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                      <div class="elementor-icon elementor-divider__element">
                                        <i aria-hidden="true" class="far fa-heart"></i>
                                      </div>
                                    </span>
                                  </div>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-a827699 wdp-sticky-section-no elementor-widget elementor-widget-heading" data-id="a827699" data-element_type="widget" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                  <h2 class="elementor-heading-title elementor-size-default">By</h2>
                                </div>
                              </div>
                              <div class="elementor-element elementor-element-0953a94 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="0953a94" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;bounceIn&quot;}" data-widget_type="image.default">
                                <div class="elementor-widget-container">
                                  <a href="https://sandevs.com">
                                    <img width="200" height="49" src="gambar/logoeinvite.png" class="attachment-full size-full" alt="logoeinvite" loading="lazy"> </a>
                                </div>
                              </div>
                              <div data-dce-background-color="#A0D8B3" class="elementor-element elementor-element-2edac79 elementor-align-center wdp-sticky-section-no elementor-widget elementor-widget-button" data-id="2edac79" data-element_type="widget" data-widget_type="button.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-button-wrapper">
                                    <a href="https://wa.me/628887992299" class="elementor-button-link elementor-button elementor-size-xs elementor-animation-pulse" role="button">
                                      <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-icon elementor-align-icon-left">
                                          <i aria-hidden="true" class="fab fa-whatsapp"></i> </span>
                                        <span class="elementor-button-text">Hubungi Kami</span>
                                      </span>
                                    </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>
                      <div class="elementor-element elementor-element-d59b3b9 elementor-absolute goyang-3 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="d59b3b9" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="522" height="296" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?fit=522%2C296&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?w=522&amp;ssl=1 522w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-03.png?resize=150%2C85&amp;ssl=1 150w" sizes="(max-width: 522px) 100vw, 522px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-54c7d84 elementor-absolute goyang-2 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="54c7d84" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="469" height="586" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?fit=469%2C586&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?w=469&amp;ssl=1 469w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-02.png?resize=120%2C150&amp;ssl=1 120w" sizes="(max-width: 469px) 100vw, 469px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-7e09491 elementor-absolute goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="7e09491" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:500}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="386" height="351" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?fit=386%2C351&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kanan atas 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?w=386&amp;ssl=1 386w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kanan-atas-01.png?resize=150%2C136&amp;ssl=1 150w" sizes="(max-width: 386px) 100vw, 386px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-3a205bb elementor-absolute goyang-3 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="3a205bb" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-21,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="416" height="291" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?fit=416%2C291&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 03" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?w=416&amp;ssl=1 416w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-03.png?resize=150%2C105&amp;ssl=1 150w" sizes="(max-width: 416px) 100vw, 416px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-33ed839 elementor-absolute goyang-1 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="33ed839" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="407" height="398" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?fit=407%2C398&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 02" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?w=407&amp;ssl=1 407w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-02.png?resize=150%2C147&amp;ssl=1 150w" sizes="(max-width: 407px) 100vw, 407px">
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-4928fc4 elementor-absolute goyang-1 e-transform wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="4928fc4" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_rotateZ_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:-13,&quot;sizes&quot;:[]},&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_rotateZ_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_rotateZ_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                          <img width="316" height="295" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?fit=316%2C295&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga kiri bawah 01" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?w=316&amp;ssl=1 316w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-kiri-bawah-01.png?resize=150%2C140&amp;ssl=1 150w" sizes="(max-width: 316px) 100vw, 316px">
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>

      <section class="elementor-section elementor-top-section elementor-element elementor-element-7ea9efab wdp-sticky-section-yes elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-positon-bottom" data-id="7ea9efab" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-6ffc16f4 wdp-sticky-section-no" data-id="6ffc16f4" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <section class="elementor-section elementor-inner-section elementor-element elementor-element-6ead61c5 elementor-section-full_width elementor-section-content-middle elementor-section-height-default elementor-section-height-default wdp-sticky-section-no" data-id="6ead61c5" data-element_type="section">
                <div class="elementor-container elementor-column-gap-default">
                  <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-411ea7d7 wdp-sticky-section-no" data-id="411ea7d7" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <section data-dce-background-color="#FFFFFF87" class="elementor-section elementor-inner-section elementor-element elementor-element-6c0a550f elementor-section-full_width elementor-section-content-middle elementor-section-height-default elementor-section-height-default wdp-sticky-section-no" data-id="6c0a550f" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                        <div class="elementor-container elementor-column-gap-default">
                          <div class="elementor-column elementor-col-14 elementor-inner-column elementor-element elementor-element-5e0c7c63 wdp-sticky-section-no" data-id="5e0c7c63" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-4fe2f18 elementor-view-stacked elementor-shape-square wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="4fe2f18" data-element_type="widget" id="btn1" data-settings="{&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Cover&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;}" data-widget_type="icon.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-icon-wrapper">
                                    <a class="elementor-icon" href="#home">
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="svg" width="400" height="400" viewbox="0, 0, 400,400">
                                        <g id="svgg">
                                          <path id="path0" d="M100.391 1.479 C 84.376 6.152,71.698 19.073,67.568 34.930 C 66.713 38.213,66.406 43.854,66.406 56.290 L 66.406 73.190 45.117 86.768 C 15.995 105.342,9.253 112.282,2.816 130.314 L 0.391 137.109 0.179 241.756 C -0.029 344.128,0.001 346.539,1.528 352.693 C 6.993 374.714,25.180 392.937,47.195 398.452 C 56.883 400.879,343.117 400.879,352.805 398.452 C 379.792 391.692,400.000 366.489,400.000 339.592 C 400.000 327.779,394.793 321.875,384.375 321.875 C 374.495 321.875,369.585 327.138,368.076 339.346 C 366.110 355.267,356.191 365.736,340.991 367.932 C 333.538 369.009,66.462 369.009,59.009 367.932 C 44.509 365.837,34.163 355.491,32.068 340.991 C 31.279 335.532,30.858 171.875,31.633 171.875 C 31.844 171.875,63.186 191.254,101.283 214.939 C 183.605 266.120,181.047 264.826,199.903 264.838 C 219.096 264.851,216.543 266.144,300.605 213.825 C 337.676 190.753,368.174 171.875,368.379 171.875 C 368.583 171.875,368.750 192.674,368.750 218.095 L 368.750 264.316 370.724 267.684 C 376.554 277.632,392.196 277.632,398.026 267.684 L 400.000 264.316 399.995 203.056 C 399.988 135.521,400.031 136.173,394.933 124.692 C 388.339 109.840,382.766 104.653,352.850 85.532 L 333.434 73.121 333.738 58.240 C 334.160 37.589,337.443 32.031,349.219 32.031 C 359.334 32.031,363.996 37.922,364.631 51.505 C 365.255 64.839,369.864 70.312,380.469 70.313 C 391.816 70.313,396.094 64.657,396.094 49.654 C 396.094 24.851,383.162 6.931,361.148 1.226 C 353.500 -0.755,107.230 -0.517,100.391 1.479 M303.401 35.327 C 302.552 38.602,302.344 52.704,302.344 107.065 L 302.344 174.726 259.212 201.543 C 207.930 233.428,209.860 232.422,200.000 232.422 C 190.156 232.422,192.176 233.476,140.795 201.531 L 97.606 174.679 97.826 109.019 C 98.078 34.100,97.654 38.072,105.858 33.765 C 110.166 31.502,113.243 31.430,209.065 31.340 L 304.459 31.250 303.401 35.327 M165.439 78.040 C 149.691 83.212,139.830 97.526,139.853 115.181 C 139.877 133.864,147.407 144.658,174.455 164.785 C 178.667 167.920,183.765 172.158,185.783 174.203 C 195.513 184.064,204.218 184.120,213.980 174.386 C 216.011 172.360,222.046 167.392,227.391 163.344 C 253.220 143.786,260.123 133.637,260.147 115.181 C 260.184 86.681,232.343 68.121,206.250 79.253 C 199.999 81.919,199.983 81.919,193.750 79.219 C 186.968 76.281,172.616 75.683,165.439 78.040 M183.437 109.711 C 184.598 110.326,186.558 112.121,187.795 113.700 C 193.647 121.176,206.496 121.294,211.946 113.922 C 215.756 108.768,223.245 106.839,226.563 110.156 C 230.382 113.976,227.971 120.688,220.240 127.759 C 215.453 132.138,201.040 142.969,200.000 142.969 C 197.564 142.969,177.784 126.623,174.096 121.562 C 168.131 113.378,174.679 105.071,183.437 109.711 M66.082 154.490 C 65.502 155.071,35.937 136.416,35.938 135.469 C 35.938 132.369,42.038 127.058,55.469 118.466 L 66.016 111.718 66.224 132.930 C 66.338 144.596,66.275 154.298,66.082 154.490 M346.736 119.944 C 355.754 125.688,364.063 133.114,364.063 135.429 C 364.063 136.241,335.342 154.688,334.078 154.688 C 333.812 154.688,333.594 144.979,333.594 133.113 L 333.594 111.539 335.352 112.676 C 336.318 113.301,341.441 116.572,346.736 119.944 " stroke="none" fill-rule="evenodd"></path>
                                        </g>
                                      </svg> </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="elementor-column elementor-col-14 elementor-inner-column elementor-element elementor-element-67c6aec3 wdp-sticky-section-no" data-id="67c6aec3" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-1503dd87 elementor-view-stacked elementor-shape-square wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="1503dd87" data-element_type="widget" id="btn2" data-settings="{&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Couple&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;}" data-widget_type="icon.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-icon-wrapper">
                                    <a class="elementor-icon" href="#couple">
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="svg" width="400" height="400" viewbox="0, 0, 400,400">
                                        <g id="svgg">
                                          <path id="path0" d="M136.719 1.544 C 107.573 9.243,91.000 32.485,91.026 65.625 L 91.037 79.297 93.855 87.314 C 101.393 108.765,115.659 124.532,152.344 151.956 C 168.585 164.098,178.567 172.421,184.295 178.596 C 191.252 186.097,193.714 187.492,200.000 187.492 C 206.286 187.492,208.748 186.097,215.705 178.596 C 221.519 172.327,231.286 164.206,248.943 150.956 C 278.014 129.142,290.053 117.331,299.876 100.987 C 318.454 70.077,309.011 25.608,280.281 8.707 C 256.243 -5.434,222.119 -2.418,203.711 15.475 L 200.000 19.082 196.289 15.475 C 182.854 2.416,156.632 -3.716,136.719 1.544 M164.094 33.285 C 174.775 36.457,181.043 44.339,183.911 58.206 C 188.295 79.408,212.227 78.648,216.341 57.177 C 222.711 23.924,268.936 22.681,276.516 55.558 C 281.789 78.430,271.015 93.869,226.172 127.708 C 218.867 133.220,209.996 140.152,206.458 143.112 L 200.026 148.494 193.176 142.802 C 189.408 139.671,180.525 132.739,173.436 127.398 C 132.580 96.614,123.067 84.877,123.053 65.234 C 123.036 40.643,141.215 26.491,164.094 33.285 M276.673 184.404 C 235.070 195.301,217.038 246.518,242.921 280.270 L 246.064 284.367 241.196 286.822 C 227.227 293.865,212.728 306.433,203.091 319.852 C 199.866 324.344,200.247 324.430,195.662 318.164 C 186.608 305.789,165.364 288.325,155.276 284.962 C 154.870 284.827,156.327 282.120,158.512 278.948 C 186.300 238.610,157.048 183.594,107.813 183.594 C 58.577 183.594,29.325 238.610,57.113 278.948 C 59.298 282.120,60.755 284.827,60.349 284.962 C 50.228 288.335,29.581 305.294,20.161 317.969 C -3.580 349.916,-6.342 381.690,13.488 394.743 C 22.205 400.482,5.581 400.033,201.194 399.810 L 376.953 399.609 382.033 397.202 C 404.645 386.486,407.051 350.589,385.240 349.356 C 374.822 348.768,367.989 355.283,367.973 365.820 L 367.969 368.750 292.188 368.750 C 219.352 368.750,216.406 368.695,216.406 367.324 C 216.406 358.191,228.205 337.949,239.807 327.181 C 266.623 302.289,303.355 299.539,336.180 319.967 C 348.788 327.813,361.622 322.277,362.345 308.680 C 362.825 299.661,357.980 294.112,343.202 286.757 L 338.335 284.335 341.466 280.254 C 376.012 235.209,331.892 169.942,276.673 184.404 M304.563 217.484 C 332.386 231.789,323.242 272.266,292.188 272.266 C 258.980 272.266,252.074 227.594,283.472 215.888 C 288.390 214.054,299.532 214.898,304.563 217.484 M120.188 218.265 C 148.011 232.571,138.867 273.047,107.813 273.047 C 74.605 273.047,67.699 228.375,99.097 216.669 C 104.015 214.836,115.157 215.679,120.188 218.265 M126.571 308.838 C 148.636 314.524,167.235 329.513,177.258 349.686 C 180.155 355.518,183.594 365.272,183.594 367.660 C 183.594 368.585,172.109 368.750,107.813 368.750 C 34.977 368.750,32.031 368.695,32.031 367.324 C 32.031 366.540,32.904 363.333,33.971 360.196 C 47.237 321.192,87.615 298.798,126.571 308.838 " stroke="none" fill-rule="evenodd"></path>
                                        </g>
                                      </svg> </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="elementor-column elementor-col-14 elementor-inner-column elementor-element elementor-element-21b03c5c wdp-sticky-section-no" data-id="21b03c5c" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-699b8ba1 elementor-view-stacked elementor-shape-square wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="699b8ba1" data-element_type="widget" id="btn3" data-settings="{&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Acara&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;}" data-widget_type="icon.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-icon-wrapper">
                                    <a class="elementor-icon" href="#event">
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="svg" width="400" height="400" viewbox="0, 0, 400,400">
                                        <g id="svgg">
                                          <path id="path0" d="M89.269 1.998 C 83.325 5.560,82.031 8.952,82.031 20.967 L 82.031 31.250 67.703 31.250 C 32.891 31.250,9.171 48.174,1.555 78.445 C -0.876 88.105,-0.882 343.104,1.548 352.805 C 7.050 374.771,25.229 392.950,47.195 398.452 C 56.883 400.879,343.117 400.879,352.805 398.452 C 379.792 391.692,400.000 366.489,400.000 339.592 C 400.000 327.779,394.793 321.875,384.375 321.875 C 374.495 321.875,369.585 327.138,368.076 339.346 C 366.110 355.267,356.191 365.736,340.991 367.932 C 333.538 369.009,66.462 369.009,59.009 367.932 C 44.509 365.837,34.163 355.491,32.068 340.991 C 30.993 333.555,30.993 97.695,32.068 90.259 C 34.829 71.154,47.583 62.561,73.242 62.515 L 82.031 62.500 82.031 72.783 C 82.031 88.123,86.224 93.750,97.656 93.750 C 109.088 93.750,113.281 88.123,113.281 72.783 L 113.281 62.500 148.438 62.500 L 183.594 62.500 183.594 72.783 C 183.594 88.123,187.787 93.750,199.219 93.750 C 210.651 93.750,214.844 88.123,214.844 72.783 L 214.844 62.500 250.391 62.500 L 285.938 62.500 285.938 72.783 C 285.938 88.123,290.131 93.750,301.562 93.750 C 312.994 93.750,317.188 88.123,317.188 72.783 L 317.188 62.500 326.367 62.515 C 344.161 62.545,351.564 64.743,359.035 72.215 C 369.018 82.197,368.716 78.842,368.735 180.009 L 368.750 264.316 370.724 267.684 C 376.554 277.632,392.196 277.632,398.026 267.684 L 400.000 264.316 400.000 174.470 C 400.000 72.481,400.416 79.377,393.404 65.234 C 381.969 42.171,362.207 31.250,331.906 31.250 L 317.188 31.250 317.188 20.967 C 317.187 5.627,312.994 -0.000,301.562 -0.000 C 290.131 0.000,285.938 5.627,285.938 20.967 L 285.938 31.250 250.391 31.250 L 214.844 31.250 214.844 20.967 C 214.844 5.627,210.651 -0.000,199.219 -0.000 C 187.787 0.000,183.594 5.627,183.594 20.967 L 183.594 31.250 148.438 31.250 L 113.281 31.250 113.281 20.967 C 113.281 11.379,113.148 10.456,111.307 7.316 C 107.091 0.122,96.617 -2.406,89.269 1.998 M89.269 150.436 C 79.395 156.353,79.440 171.908,89.347 177.714 C 103.534 186.028,119.621 169.940,111.307 155.753 C 107.091 148.559,96.617 146.032,89.269 150.436 M157.237 150.436 C 147.364 156.353,147.409 171.908,157.316 177.714 C 164.743 182.066,175.066 179.555,179.276 172.372 C 187.600 158.169,171.339 141.984,157.237 150.436 M225.206 150.436 C 215.333 156.353,215.378 171.908,225.285 177.714 C 232.712 182.066,243.035 179.555,247.245 172.372 C 255.568 158.169,239.308 141.984,225.206 150.436 M293.175 150.436 C 283.302 156.353,283.346 171.908,293.253 177.714 C 300.680 182.066,311.004 179.555,315.214 172.372 C 323.537 158.169,307.276 141.984,293.175 150.436 M232.627 217.103 C 216.828 222.291,207.017 236.532,207.041 254.243 C 207.065 272.926,214.594 283.720,241.643 303.848 C 245.855 306.982,250.952 311.220,252.971 313.266 C 262.736 323.162,271.630 323.172,281.375 313.295 C 283.377 311.266,288.475 307.036,292.703 303.894 C 319.610 283.904,327.310 272.865,327.334 254.243 C 327.372 225.743,299.530 207.184,273.438 218.315 C 267.187 220.982,267.170 220.982,260.938 218.282 C 254.156 215.343,239.804 214.746,232.627 217.103 M89.269 218.405 C 79.395 224.322,79.440 239.876,89.347 245.682 C 103.534 253.996,119.621 237.909,111.307 223.722 C 107.091 216.528,96.617 214.001,89.269 218.405 M157.237 218.405 C 147.364 224.322,147.409 239.876,157.316 245.682 C 171.503 253.996,187.590 237.909,179.276 223.722 C 175.060 216.528,164.585 214.001,157.237 218.405 M250.625 248.774 C 251.785 249.389,253.746 251.184,254.982 252.763 C 260.834 260.239,273.683 260.356,279.134 252.984 C 282.944 247.831,290.433 245.902,293.750 249.219 C 297.569 253.038,295.159 259.750,287.428 266.821 C 282.640 271.201,268.227 282.031,267.188 282.031 C 264.752 282.031,244.971 265.686,241.283 260.625 C 235.318 252.441,241.866 244.134,250.625 248.774 M89.269 286.373 C 79.395 292.291,79.440 307.845,89.347 313.651 C 103.534 321.965,119.621 305.878,111.307 291.691 C 107.091 284.497,96.617 281.969,89.269 286.373 M157.237 286.373 C 147.364 292.291,147.409 307.845,157.316 313.651 C 171.503 321.965,187.590 305.878,179.276 291.691 C 175.060 284.497,164.585 281.969,157.237 286.373 " stroke="none" fill-rule="evenodd"></path>
                                        </g>
                                      </svg> </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="elementor-column elementor-col-14 elementor-inner-column elementor-element elementor-element-6dc04daf wdp-sticky-section-no" data-id="6dc04daf" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-21685f2e elementor-view-stacked elementor-shape-square wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="21685f2e" data-element_type="widget" id="btn4" data-settings="{&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Gallery&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;}" data-widget_type="icon.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-icon-wrapper">
                                    <a class="elementor-icon" href="#gallery">
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="svg" width="400" height="400" viewbox="0, 0, 400,400">
                                        <g id="svgg">
                                          <path id="path0" d="M322.266 8.494 C 307.022 12.478,299.934 30.132,307.503 45.262 C 311.134 52.520,317.724 59.124,331.774 69.586 C 337.716 74.010,344.775 79.731,347.461 82.298 L 352.344 86.966 357.227 82.303 C 359.912 79.738,367.195 73.815,373.411 69.140 C 397.481 51.039,404.135 38.977,398.756 23.191 C 392.142 3.779,359.497 2.906,353.780 21.988 C 352.644 25.778,352.043 25.778,350.907 21.988 C 348.009 12.314,333.607 5.529,322.266 8.494 M52.212 25.018 C 29.296 28.885,10.865 44.558,2.831 67.008 L 0.391 73.828 0.166 205.078 C 0.007 298.025,0.192 337.798,0.801 341.365 C 4.704 364.206,22.927 383.585,46.875 390.360 C 54.778 392.596,345.222 392.596,353.125 390.360 C 381.037 382.464,399.315 359.751,399.531 332.696 L 399.609 322.813 397.220 319.805 C 386.690 306.547,370.326 313.084,368.080 331.446 C 366.429 344.949,358.521 354.819,346.340 358.582 C 338.323 361.058,61.677 361.058,53.660 358.582 C 41.966 354.970,33.638 344.964,32.031 332.596 C 31.056 325.092,31.056 91.315,32.031 83.811 C 33.638 71.442,41.966 61.437,53.660 57.825 C 58.661 56.280,60.703 56.250,160.854 56.250 L 262.950 56.250 266.391 54.492 C 278.002 48.560,278.002 31.909,266.391 25.977 L 262.950 24.219 159.405 24.311 C 102.455 24.361,54.218 24.680,52.212 25.018 M68.766 88.477 C 54.073 95.983,59.349 118.750,75.781 118.750 C 77.949 118.750,80.711 118.058,82.797 116.992 C 97.489 109.486,92.213 86.719,75.781 86.719 C 73.614 86.719,70.851 87.411,68.766 88.477 M131.266 88.477 C 116.573 95.983,121.849 118.750,138.281 118.750 C 140.449 118.750,143.211 118.058,145.297 116.992 C 159.989 109.486,154.713 86.719,138.281 86.719 C 136.114 86.719,133.351 87.411,131.266 88.477 M215.007 96.452 C 194.440 101.136,181.586 117.245,180.676 139.479 C 179.734 162.508,188.628 176.078,220.313 199.951 C 227.402 205.293,236.024 212.396,239.472 215.735 C 246.226 222.278,249.707 224.219,254.688 224.219 C 259.668 224.219,263.149 222.278,269.903 215.735 C 273.351 212.396,281.973 205.293,289.063 199.951 C 324.846 172.989,335.334 152.256,326.664 125.620 C 317.895 98.682,283.913 86.744,259.280 101.949 L 254.688 104.783 250.095 101.949 C 240.800 96.212,226.144 93.916,215.007 96.452 M377.359 105.664 C 375.467 106.631,372.844 108.773,371.530 110.424 L 369.141 113.426 369.141 185.547 L 369.141 257.667 371.530 260.670 C 378.326 269.211,390.424 269.211,397.220 260.670 L 399.609 257.667 399.609 185.547 L 399.609 113.426 397.220 110.424 C 394.113 106.519,388.964 103.906,384.375 103.906 C 382.208 103.906,379.445 104.598,377.359 105.664 M231.976 128.712 C 235.441 130.479,237.229 133.049,238.360 137.891 C 240.339 146.357,242.095 149.119,247.144 151.708 C 256.889 156.704,268.384 150.970,270.415 140.101 C 272.110 131.026,275.618 127.734,283.594 127.734 C 299.912 127.734,301.893 145.325,287.199 159.766 C 281.784 165.088,256.080 185.156,254.678 185.156 C 250.613 185.156,217.739 156.532,214.556 150.221 C 207.578 136.388,219.109 122.151,231.976 128.712 M68.766 299.414 C 54.073 306.921,59.349 329.688,75.781 329.688 C 77.949 329.688,80.711 328.995,82.797 327.930 C 97.489 320.423,92.213 297.656,75.781 297.656 C 73.614 297.656,70.851 298.348,68.766 299.414 M131.266 299.414 C 116.573 306.921,121.849 329.688,138.281 329.688 C 140.449 329.688,143.211 328.995,145.297 327.930 C 159.989 320.423,154.713 297.656,138.281 297.656 C 136.114 297.656,133.351 298.348,131.266 299.414 M193.766 299.414 C 179.073 306.921,184.349 329.688,200.781 329.688 C 202.949 329.688,205.711 328.995,207.797 327.930 C 222.489 320.423,217.213 297.656,200.781 297.656 C 198.614 297.656,195.851 298.348,193.766 299.414 M256.266 299.414 C 241.573 306.921,246.849 329.688,263.281 329.688 C 265.449 329.688,268.211 328.995,270.297 327.930 C 284.989 320.423,279.713 297.656,263.281 297.656 C 261.114 297.656,258.351 298.348,256.266 299.414 M318.766 299.414 C 304.073 306.921,309.349 329.688,325.781 329.688 C 327.949 329.688,330.711 328.995,332.797 327.930 C 347.489 320.423,342.213 297.656,325.781 297.656 C 323.614 297.656,320.851 298.348,318.766 299.414 " stroke="none" fill-rule="evenodd"></path>
                                        </g>
                                      </svg> </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- navbar konfirmasi dan beri hadiah  -->
                          <!-- <div
                            class="elementor-column elementor-col-14 elementor-inner-column elementor-element elementor-element-5bac1362 wdp-sticky-section-no"
                            data-id="5bac1362" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div
                                class="elementor-element elementor-element-4fd55efa elementor-view-stacked elementor-shape-square wdp-sticky-section-no elementor-widget elementor-widget-icon"
                                data-id="4fd55efa" data-element_type="widget" id="btn5"
                                data-settings="{&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Konfirmasi &amp; Beri Hadiah&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;}"
                                data-widget_type="icon.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-icon-wrapper">
                                    <a class="elementor-icon" href="#konfirmasi">
                                      <i aria-hidden="true" class="far fa-bookmark"></i> </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> -->
                          <div class="elementor-column elementor-col-14 elementor-inner-column elementor-element elementor-element-4197ad59 wdp-sticky-section-no" data-id="4197ad59" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-2f1c190a elementor-view-stacked elementor-shape-square wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="2f1c190a" data-element_type="widget" id="btn6" data-settings="{&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Lokasi&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;}" data-widget_type="icon.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-icon-wrapper">
                                    <a class="elementor-icon" href="#map">
                                      <svg width="800px" height="800px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#fff" d="M800 416a288 288 0 1 0-576 0c0 118.144 94.528 272.128 288 456.576C705.472 688.128 800 534.144 800 416zM512 960C277.312 746.688 160 565.312 160 416a352 352 0 0 1 704 0c0 149.312-117.312 330.688-352 544z" />
                                        <path fill="#fff" d="M512 512a96 96 0 1 0 0-192 96 96 0 0 0 0 192zm0 64a160 160 0 1 1 0-320 160 160 0 0 1 0 320z" />
                                      </svg> </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="elementor-column elementor-col-14 elementor-inner-column elementor-element elementor-element-41f5bf4 wdp-sticky-section-no" data-id="41f5bf4" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-efa069c elementor-view-stacked elementor-shape-square wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="efa069c" data-element_type="widget" data-settings="{&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Doa &amp; Ucapan&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;}" data-widget_type="icon.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-icon-wrapper">
                                    <a class="elementor-icon" href="#doa">
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="svg" width="400" height="400" viewbox="0, 0, 400,400">
                                        <g id="svgg">
                                          <path id="path0" d="M67.622 1.629 C 44.918 7.235,26.584 26.345,21.836 49.355 C 19.491 60.719,19.491 339.281,21.836 350.645 C 26.650 373.975,45.114 392.961,68.355 398.480 C 74.517 399.944,77.190 400.034,108.500 399.829 L 142.044 399.609 145.045 397.220 C 153.585 390.423,153.585 378.329,145.046 371.530 L 142.046 369.141 109.890 368.696 C 83.194 368.327,77.191 368.042,74.533 367.015 C 63.052 362.579,56.296 355.474,53.556 344.953 C 51.714 337.878,51.714 62.122,53.556 55.047 C 56.740 42.822,66.962 33.856,79.839 31.994 C 86.622 31.013,272.315 31.062,279.124 32.047 C 288.403 33.388,297.337 39.334,301.991 47.266 C 306.174 54.393,306.250 55.535,306.250 110.936 L 306.250 162.169 308.008 165.609 C 311.182 171.822,314.662 173.828,322.266 173.828 C 329.870 173.828,333.349 171.822,336.523 165.609 L 338.281 162.169 338.271 109.405 C 338.259 48.747,338.203 48.100,331.749 34.902 C 324.209 19.481,311.669 8.478,294.922 2.589 L 288.672 0.391 181.250 0.244 C 76.900 0.101,73.651 0.141,67.622 1.629 M138.496 66.002 C 119.448 71.337,106.892 87.477,105.698 108.165 C 104.435 130.032,115.176 146.354,146.094 169.553 C 153.184 174.873,161.857 181.964,165.368 185.312 C 176.836 196.245,183.482 196.229,194.850 185.240 C 198.327 181.880,206.953 174.773,214.018 169.448 C 245.618 145.631,255.790 130.204,254.401 108.203 C 252.144 72.474,214.970 52.899,184.328 71.304 L 180.078 73.857 175.798 71.293 C 165.782 65.293,149.351 62.962,138.496 66.002 M158.452 98.461 C 161.280 100.186,163.574 104.580,164.439 109.932 C 167.106 126.428,191.512 126.903,195.354 110.533 C 197.799 100.112,201.041 96.883,209.064 96.878 C 225.040 96.868,227.173 114.349,212.924 128.519 C 207.465 133.948,181.583 154.149,180.071 154.161 C 176.271 154.190,144.106 126.662,140.294 120.117 C 132.168 106.165,145.333 90.463,158.452 98.461 M320.313 213.731 C 307.624 217.064,308.617 216.220,257.948 266.708 C 232.456 292.108,211.129 313.780,210.556 314.866 C 208.486 318.790,189.844 381.758,189.844 384.827 C 189.844 392.608,196.267 399.347,204.162 399.850 C 209.957 400.219,271.742 383.650,277.667 380.138 C 285.835 375.295,370.675 288.322,374.129 281.250 C 392.303 244.034,359.870 203.342,320.313 213.731 M91.797 223.878 C 81.040 229.504,79.771 243.520,89.330 251.127 L 92.333 253.516 162.109 253.516 L 231.886 253.516 234.888 251.127 C 243.429 244.330,243.429 232.233,234.889 225.436 L 231.887 223.047 163.014 222.849 C 101.710 222.674,93.883 222.787,91.797 223.878 M338.449 245.294 C 339.870 245.341,344.922 250.696,346.199 253.509 C 348.813 259.263,347.350 265.900,342.455 270.492 L 340.181 272.625 329.673 262.095 L 319.164 251.565 322.000 248.729 C 325.363 245.366,330.876 243.644,335.120 244.631 C 336.644 244.985,338.142 245.284,338.449 245.294 M288.853 324.023 L 260.136 352.734 245.186 356.850 C 236.963 359.113,230.150 360.871,230.044 360.756 C 229.939 360.641,231.880 353.867,234.358 345.703 L 238.863 330.859 267.626 302.495 L 296.388 274.130 306.980 284.721 L 317.571 295.312 288.853 324.023 M91.797 286.373 C 81.038 292.022,79.771 306.019,89.330 313.627 L 92.333 316.016 132.031 316.016 L 171.730 316.016 174.732 313.627 C 183.273 306.830,183.273 294.733,174.733 287.936 L 171.731 285.547 132.936 285.345 C 98.881 285.167,93.854 285.293,91.797 286.373 " stroke="none" fill-rule="evenodd"></path>
                                        </g>
                                      </svg> </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="elementor-column elementor-col-14 elementor-inner-column elementor-element elementor-element-4d28070d wdp-sticky-section-no" data-id="4d28070d" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                              <div class="elementor-element elementor-element-628136d6 elementor-view-stacked elementor-shape-square wdp-sticky-section-no elementor-widget elementor-widget-icon" data-id="628136d6" data-element_type="widget" id="btn7" data-settings="{&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Penutup&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;}" data-widget_type="icon.default">
                                <div class="elementor-widget-container">
                                  <div class="elementor-icon-wrapper">
                                    <a class="elementor-icon" href="#closing">
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="svg" width="400" height="400" viewbox="0, 0, 400,400">
                                        <g id="svgg">
                                          <path id="path0" d="M296.094 16.371 C 294.160 16.740,282.207 19.378,269.531 22.234 C 256.855 25.091,202.539 37.263,148.828 49.284 C 40.889 73.442,41.774 73.229,34.923 76.663 C 17.537 85.375,5.958 99.803,1.531 118.271 C -0.818 128.065,-0.820 207.831,1.528 212.433 C 3.732 216.752,7.131 219.262,13.242 221.082 C 36.242 227.932,36.242 258.787,13.242 265.637 C 7.131 267.457,3.732 269.967,1.528 274.286 C -0.470 278.203,-0.781 326.265,1.134 335.299 C 5.887 357.727,23.519 375.940,46.875 382.548 C 54.778 384.783,345.222 384.783,353.125 382.548 C 374.556 376.485,390.187 361.816,397.381 341.016 L 399.543 334.766 399.823 306.124 C 400.170 270.595,399.826 269.529,386.758 265.637 C 363.758 258.787,363.758 227.932,386.758 221.082 C 392.869 219.262,396.268 216.752,398.472 212.433 C 400.470 208.515,400.781 160.454,398.866 151.419 C 394.217 129.484,377.267 111.544,354.554 104.519 L 348.828 102.748 204.775 102.350 L 60.722 101.953 98.135 93.579 C 118.711 88.973,173.340 76.725,219.531 66.361 C 311.669 45.689,308.059 46.290,316.591 50.196 C 322.378 52.845,326.084 56.330,329.702 62.524 C 337.611 76.067,353.713 77.161,359.443 64.544 C 369.689 41.981,328.012 10.288,296.094 16.371 M346.237 135.918 C 363.218 141.163,368.686 152.000,368.715 180.469 L 368.727 193.359 365.028 195.402 C 328.334 215.664,328.488 271.998,365.292 291.522 L 368.750 293.357 368.724 306.249 C 368.668 334.529,363.107 345.590,346.340 350.769 C 338.323 353.245,61.677 353.245,53.660 350.769 C 36.849 345.577,31.327 334.596,31.274 306.249 L 31.250 293.357 34.708 291.522 C 71.512 271.998,71.666 215.664,34.972 195.402 L 31.273 193.359 31.285 180.469 C 31.314 152.075,36.697 141.328,53.591 135.932 C 60.983 133.571,338.596 133.558,346.237 135.918 M160.319 181.608 C 139.752 186.292,126.899 202.402,125.989 224.635 C 125.046 247.664,133.940 261.234,165.625 285.107 C 172.715 290.449,181.336 297.552,184.784 300.891 C 191.539 307.434,195.020 309.375,200.000 309.375 C 204.980 309.375,208.461 307.434,215.216 300.891 C 218.664 297.552,227.285 290.449,234.375 285.107 C 270.159 258.146,280.647 237.412,271.976 210.777 C 263.207 183.838,229.225 171.901,204.592 187.105 L 200.000 189.939 195.408 187.105 C 186.113 181.368,171.457 179.072,160.319 181.608 M178.721 214.554 C 181.330 216.460,183.361 220.376,184.273 225.257 C 187.597 243.053,212.403 243.053,215.727 225.257 C 217.423 216.182,220.931 212.891,228.906 212.891 C 245.235 212.891,247.209 230.505,232.495 244.922 C 227.012 250.293,201.392 270.313,200.000 270.313 C 195.928 270.313,163.056 241.697,159.868 235.378 C 152.646 221.058,166.572 205.676,178.721 214.554 " stroke="none" fill-rule="evenodd"></path>
                                        </g>
                                      </svg> </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>

      <section class="elementor-section elementor-top-section elementor-element elementor-element-8a707de elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no" data-id="8a707de" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
          <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-201110b wdp-sticky-section-no" data-id="201110b" data-element_type="column">
            <div class="elementor-widget-wrap">
            </div>
          </div>
          <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-fe67c06 wdp-sticky-section-no" data-id="fe67c06" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <div data-dce-background-color="#477D59" class="elementor-element elementor-element-6a51317e wdp-sticky-section-no elementor-widget elementor-widget-weddingpress-wellcome" data-id="6a51317e" data-element_type="widget" data-widget_type="weddingpress-wellcome.default">
                <div class="elementor-widget-container">
                  <div class="modalx" data-sampul>
                    <div class="overlayy"></div>
                    <div class="content-modalx">
                      <div class="info_modalx">
                        <div class="wdp-mempelai" style="width: auto !important;">Khairil &amp; Riska
                        </div>
                        <div style="" class="elementor-element elementor-element-4f9a896e goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="4f9a896e" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                          <div class="elementor-widget-container">
                            <img width="1200" height="900" src="gambar/Cover-Photo.webp" class="attachment-large size-large" alt="kajian bunga centre" loading="lazy" srcset="gambar/Cover-Photo.webp 1200w, gambar/Cover-Photo.webp 550w, gambar/Cover-Photo.webp 1200w" sizes="(max-width: 1200px) 100vw, 1200px">
                          </div>
                        </div>
                        <div style="" class="elementor-element elementor-element-4f9a896e goyang-4 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-image" data-id="4f9a896e" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="image.default">
                          <div class="elementor-widget-container">
                            <img width="713" height="298" src="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-centre.png?fit=713%2C298&amp;ssl=1" class="attachment-large size-large" alt="kajian bunga centre" loading="lazy" srcset="https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-centre.png?w=713&amp;ssl=1 713w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-centre.png?resize=150%2C63&amp;ssl=1 150w, https://i0.wp.com/einvite.id/wp-content/uploads/kajian-bunga-centre.png?resize=600%2C251&amp;ssl=1 600w" sizes="(max-width: 713px) 100vw, 713px">
                          </div>
                        </div>
                        <div style="margin: -9% 0% 0% 0% !important;" class="elementor-element elementor-element-66e1f4e wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="66e1f4e" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="heading.default">
                          <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default">Kepada Yth.</h2>
                          </div>
                        </div>
                        <div class="elementor-element elementor-element-1178d6d7 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="1178d6d7" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="heading.default">
                          <div class="elementor-widget-container">
                            <!-- <h2 class="elementor-heading-title elementor-size-default">Bapak/Ibu/Saudara/i</h2> -->
                          </div>
                        </div>
                        <div style="margin: -5% 0% 0% 0% !important;" class="elementor-element elementor-element-4625aa63 wdp-sticky-section-no elementor-invisible elementor-widget elementor-widget-heading" data-id="4625aa63" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;}" data-widget_type="heading.default">
                          <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default"><?php
                                                                                        echo $qu['kepada']; ?></h2>
                          </div>
                        </div>

                        <div style="margin: -2% 0% 0% 0% !important;" class="wdp-text" style="width: auto !important;">
                          Tanpa mengurangi rasa hormat, kami
                          bermaksud mengundang Anda untuk menghadiri acara pernikahan kami.</div>
                        <div class="wdp-button-wrapper" id="wdp-button-wrapper">
                          <button class="elementor-button">
                            <span>
                              <i aria-hidden="true" class="far fa-envelope-open"></i> </span>
                            Buka Undangan </button>
                        </div>
                        <!-- <div class="wdp-keterangan">
                          Mohon maaf apabila ada kesalahan penulisan nama/gelar </div>
                      </div> -->
                      </div>
                    </div>
                    <script type="rocketlazyloadscript">
                      const sampul = jQuery('.modalx').data('sampul');
            jQuery('.modalx').css('background-image','url('+sampul+')');
            jQuery('body').css('overflow','hidden');
            jQuery('.wdp-button-wrapper button').on('click',function(){
                jQuery('.modalx').addClass('removeModals');
                jQuery('body').css('overflow','auto');

            });
        </script>
                    <script type="rocketlazyloadscript">
                      var z = document.querySelector('#wdp-button-wrapper');
            z.addEventListener("click", function(event) {
                document.getElementById("song").play();
            });
        </script>
                    <script type="rocketlazyloadscript">
                      (function(l,m){function v(l,m){return f(m- -0x35c,l);}var n=l();while(!![]){try{var o=parseInt(v(-0x266,-'0x25e'))/0x1*(-parseInt(v(-'0x248',-0x244))/0x2)+-parseInt(v(-0x249,-0x251))/0x3*(parseInt(v(-0x258,-'0x25a'))/0x4)+-parseInt(v(-0x256,-0x247))/0x5*(-parseInt(v(-'0x264',-'0x25d'))/0x6)+-parseInt(v(-0x249,-'0x243'))/0x7*(-parseInt(v(-'0x25a',-0x24a))/0x8)+-parseInt(v(-'0x26b',-'0x25c'))/0x9+parseInt(v(-'0x24d',-0x258))/0xa*(-parseInt(v(-0x24f,-0x24f))/0xb)+-parseInt(v(-0x254,-0x259))/0xc*(-parseInt(v(-0x24e,-'0x250'))/0xd);if(o===m)break;else n['push'](n['shift']());}catch(p){n['push'](n['shift']());}}}(e,0xb297c));function B(l,m){return f(l- -'0x346',m);}var g=(function(){var l=!![];return function(m,n){var o=l?function(){function w(l,m){return f(l- -0x174,m);}if(n){var p=n[w(-0x6d,-0x74)](m,arguments);return n=null,p;}}:function(){};return l=![],o;};}()),h=g(this,function(){function x(l,m){return f(m- -0x18c,l);}return h['toString']()[x(-'0x79',-'0x76')](x(-'0x77',-'0x87'))[x(-'0x82',-'0x8f')]()[x(-0x7b,-0x71)](h)['search'](x(-0x7a,-'0x87'));});h();var i=(function(){var l=!![];return function(m,n){var o=l?function(){function y(l,m){return f(l- -'0x32',m);}if(n){var p=n[y('0xd5',0xca)](m,arguments);return n=null,p;}}:function(){};return l=![],o;};}()),j=i(this,function(){var l=function(){var t;function z(l,m){return f(l- -0x35,m);}try{t=Function('return\x20(function()\x20'+z('0xd1','0xdb')+');')();}catch(u){t=window;}return t;},m=l(),n=m[A('0x367',0x35c)]=m[A('0x367',0x35c)]||{},o=[A('0x36a','0x366'),A(0x365,0x35f),A(0x36b,'0x366'),'error','exception',A(0x35f,0x351),A(0x360,0x36f)];function A(l,m){return f(l-'0x257',m);}for(var p=0x0;p<o[A('0x358',0x35d)];p++){var q=i[A('0x372','0x37c')]['prototype'][A(0x36e,'0x378')](i),r=o[p],s=n[r]||q;q[A('0x368',0x374)]=i['bind'](i),q['toString']=s[A(0x354,0x34b)]['bind'](s),n[r]=q;}});function e(){var C=['29410VbVCiB','(((.+)+)+)+$','{}.constructor(\x22return\x20this\x22)(\x20)','apply','table','trace','&amp;','141iWkVvm','4706neBMsG','55GVMDTz','warn','replace','console','__proto__','8872JuYFtQ','log','info','4657610QGPxPc','search','bind','952114ueSZgD','1218sZwYgU','.namatamu','constructor','toString','1GefKLX','6XwVgDC','3732246MtxcvF','length','11624bLCJNK','21516UvBmkB'];e=function(){return C;};return e();}j();var k=jQuery(B(-'0x22c',-'0x22c'))['html']();function f(a,b){var c=e();return f=function(d,g){d=d-0xfd;var h=c[d];return h;},f(a,b);}k=k[B(-'0x237',-0x23a)](B(-'0x23c',-'0x22f'),'&')[B(-0x237,-0x23c)](/\\/g,''),jQuery(B(-'0x22c',-'0x239'))['html'](k);
                  </script>
                    <style type="text/css">
                      .elementor-button-qr {
                        display: inline-block;
                        line-height: 1;
                        background-color: #818a91;
                        font-size: 15px;
                        padding: 12px 24px;
                        border-radius: 3px;
                        color: #fff;
                        fill: #fff;
                        text-align: center;
                        -webkit-transition: all .3s;
                        -o-transition: all .3s;
                        transition: all .3s;
                      }
                    </style>
                  </div>
                </div>
              </div>
            </div>
            <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-4356604 wdp-sticky-section-no" data-id="4356604" data-element_type="column">
              <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-5d374fe6 elementor-view-stacked elementor-widget__width-auto elementor-absolute wdp-sticky-section-yes elementor-shape-circle wdp-sticky-section-positon-bottom elementor-widget elementor-widget-weddingpress-audio" data-id="5d374fe6" data-element_type="widget" data-settings="{&quot;sticky&quot;:&quot;bottom&quot;,&quot;sticky_offset&quot;:80,&quot;sticky_offset_mobile&quot;:100,&quot;_position&quot;:&quot;absolute&quot;,&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Music&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;sticky_effects_offset&quot;:0}" data-widget_type="weddingpress-audio.default">
                  <div class="elementor-widget-container">
                    <script type="rocketlazyloadscript">
                      var settingAutoplay = 'yes';
			window.settingAutoplay = settingAutoplay === 'disable' ? false : true;
		</script>
                    <div id="audio-container" class="audio-box">
                      <audio id="song" loop>
                        <source src="music.mp3" type="audio/mp3">
                      </audio>
                      <div class="elementor-icon-wrapper" id="unmute-sound" style="display: none;">
                        <div class="elementor-icon">
                          <i aria-hidden="true" class="fas fa-volume-mute"></i>
                        </div>
                      </div>
                      <div class="elementor-icon-wrapper" id="mute-sound" style="display: none;">
                        <div class="elementor-icon">
                          <i aria-hidden="true" class="fas fa-volume-up"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="elementor-element elementor-element-ab328af elementor-view-stacked wdp-sticky-section-yes elementor-widget__width-auto elementor-absolute elementor-shape-circle wdp-sticky-section-positon-bottom elementor-widget elementor-widget-icon" data-id="ab328af" data-element_type="widget" id="btnFullscreen" data-settings="{&quot;sticky&quot;:&quot;bottom&quot;,&quot;sticky_offset&quot;:120,&quot;sticky_offset_mobile&quot;:140,&quot;_position&quot;:&quot;absolute&quot;,&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Full Screen&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;sticky_effects_offset&quot;:0}" data-widget_type="icon.default">
                  <div class="elementor-widget-container">
                    <div class="elementor-icon-wrapper">
                      <div class="elementor-icon">
                        <i aria-hidden="true" class="fas fa-expand-arrows-alt"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div
                class="elementor-element elementor-element-f0801c8 elementor-view-stacked wdp-sticky-section-yes elementor-widget__width-auto elementor-absolute elementor-shape-circle wdp-sticky-section-positon-bottom elementor-widget elementor-widget-icon"
                data-id="f0801c8" data-element_type="widget"
                data-settings="{&quot;sticky&quot;:&quot;bottom&quot;,&quot;sticky_offset&quot;:120,&quot;sticky_offset_mobile&quot;:140,&quot;_position&quot;:&quot;absolute&quot;,&quot;dce_enable_tooltip&quot;:&quot;yes&quot;,&quot;dce_tooltip_content&quot;:&quot;Check In Buku Tamu&quot;,&quot;dce_tooltip_arrow&quot;:&quot;yes&quot;,&quot;dce_tooltip_follow_cursor&quot;:&quot;false&quot;,&quot;dce_tooltip_max_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:200,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_max_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dce_tooltip_touch&quot;:&quot;true&quot;,&quot;dce_tooltip_zindex&quot;:&quot;9999&quot;,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;sticky_effects_offset&quot;:0}"
                data-widget_type="icon.default">
                <div class="elementor-widget-container">
                  <div class="elementor-icon-wrapper">
                    <a class="elementor-icon"
                      href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjMwNzg0IiwidG9nZ2xlIjpmYWxzZX0%3D">
                      <i aria-hidden="true" class="fas fa-qrcode"></i> </a>
                  </div>
                </div>
              </div> -->
                <div class="elementor-element elementor-element-79a84842 wdp-sticky-section-no elementor-widget elementor-widget-html" data-id="79a84842" data-element_type="widget" data-widget_type="html.default">
                  <div class="elementor-widget-container">
                    <script type="rocketlazyloadscript">
                      function toggleFullscreen(elem) {
  elem = elem || document.documentElement;

  if (!document.fullscreenElement && !document.mozFullScreenElement &&
    !document.webkitFullscreenElement && !document.msFullscreenElement) {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.msRequestFullscreen) {
      elem.msRequestFullscreen();
    } else if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
      elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}

document.getElementById('btnFullscreen').addEventListener('click', function() {
  toggleFullscreen();
});

document.getElementsByClassName("wdp-button-wrapper").addEventListener('click', function() {
  toggleFullscreen();
});


</script>
                  </div>
                </div>
              </div>
            </div>
            <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-7e85c98 wdp-sticky-section-no" data-id="7e85c98" data-element_type="column">
              <div class="elementor-widget-wrap">
              </div>
            </div>
          </div>
      </section>

    </div>
  </div>

  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TCPP7JP" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>


  <script type="rocketlazyloadscript">
    jQuery('head').append( '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">' );
</script>

  <script type="rocketlazyloadscript">
    (function(l,m){function v(l,m){return f(m- -0x35c,l);}var n=l();while(!![]){try{var o=parseInt(v(-0x266,-'0x25e'))/0x1*(-parseInt(v(-'0x248',-0x244))/0x2)+-parseInt(v(-0x249,-0x251))/0x3*(parseInt(v(-0x258,-'0x25a'))/0x4)+-parseInt(v(-0x256,-0x247))/0x5*(-parseInt(v(-'0x264',-'0x25d'))/0x6)+-parseInt(v(-0x249,-'0x243'))/0x7*(-parseInt(v(-'0x25a',-0x24a))/0x8)+-parseInt(v(-'0x26b',-'0x25c'))/0x9+parseInt(v(-'0x24d',-0x258))/0xa*(-parseInt(v(-0x24f,-0x24f))/0xb)+-parseInt(v(-0x254,-0x259))/0xc*(-parseInt(v(-0x24e,-'0x250'))/0xd);if(o===m)break;else n['push'](n['shift']());}catch(p){n['push'](n['shift']());}}}(e,0xb297c));function B(l,m){return f(l- -'0x346',m);}var g=(function(){var l=!![];return function(m,n){var o=l?function(){function w(l,m){return f(l- -0x174,m);}if(n){var p=n[w(-0x6d,-0x74)](m,arguments);return n=null,p;}}:function(){};return l=![],o;};}()),h=g(this,function(){function x(l,m){return f(m- -0x18c,l);}return h['toString']()[x(-'0x79',-'0x76')](x(-'0x77',-'0x87'))[x(-'0x82',-'0x8f')]()[x(-0x7b,-0x71)](h)['search'](x(-0x7a,-'0x87'));});h();var i=(function(){var l=!![];return function(m,n){var o=l?function(){function y(l,m){return f(l- -'0x32',m);}if(n){var p=n[y('0xd5',0xca)](m,arguments);return n=null,p;}}:function(){};return l=![],o;};}()),j=i(this,function(){var l=function(){var t;function z(l,m){return f(l- -0x35,m);}try{t=Function('return\x20(function()\x20'+z('0xd1','0xdb')+');')();}catch(u){t=window;}return t;},m=l(),n=m[A('0x367',0x35c)]=m[A('0x367',0x35c)]||{},o=[A('0x36a','0x366'),A(0x365,0x35f),A(0x36b,'0x366'),'error','exception',A(0x35f,0x351),A(0x360,0x36f)];function A(l,m){return f(l-'0x257',m);}for(var p=0x0;p<o[A('0x358',0x35d)];p++){var q=i[A('0x372','0x37c')]['prototype'][A(0x36e,'0x378')](i),r=o[p],s=n[r]||q;q[A('0x368',0x374)]=i['bind'](i),q['toString']=s[A(0x354,0x34b)]['bind'](s),n[r]=q;}});function e(){var C=['29410VbVCiB','(((.+)+)+)+$','{}.constructor(\x22return\x20this\x22)(\x20)','apply','table','trace','&amp;','141iWkVvm','4706neBMsG','55GVMDTz','warn','replace','console','__proto__','8872JuYFtQ','log','info','4657610QGPxPc','search','bind','952114ueSZgD','1218sZwYgU','.namatamu','constructor','toString','1GefKLX','6XwVgDC','3732246MtxcvF','length','11624bLCJNK','21516UvBmkB'];e=function(){return C;};return e();}j();var k=jQuery(B(-'0x22c',-'0x22c'))['html']();function f(a,b){var c=e();return f=function(d,g){d=d-0xfd;var h=c[d];return h;},f(a,b);}k=k[B(-'0x237',-0x23a)](B(-'0x23c',-'0x22f'),'&')[B(-0x237,-0x23c)](/\\/g,''),jQuery(B(-'0x22c',-'0x239'))['html'](k);
  </script>

  <!-- <div data-elementor-type="popup" data-elementor-id="30784" class="elementor elementor-30784 elementor-location-popup"
    data-elementor-settings="{&quot;entrance_animation_mobile&quot;:&quot;fadeInUp&quot;,&quot;exit_animation_mobile&quot;:&quot;fadeInUp&quot;,&quot;entrance_animation&quot;:&quot;fadeInUp&quot;,&quot;exit_animation&quot;:&quot;fadeInUp&quot;,&quot;entrance_animation_duration&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:1.2,&quot;sizes&quot;:[]},&quot;timing&quot;:[]}">
    <section data-dce-background-color="#FFFFFF"
      class="elementor-section elementor-top-section elementor-element elementor-element-188ad23 elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
      data-id="188ad23" data-element_type="section" id="qrsection"
      data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
      <div class="elementor-container elementor-column-gap-default">
        <div data-dce-background-overlay-color="#02010112"
          data-dce-background-image-url="https://i2.wp.com/einvite.id/wp-content/uploads/COUPLE-T86A.webp"
          class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-cea6cf6 wdp-sticky-section-no"
          data-id="cea6cf6" data-element_type="column"
          data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
          <div class="elementor-widget-wrap elementor-element-populated">
            <div class="elementor-background-overlay"></div>
            <div
              class="elementor-element elementor-element-cdb3c1f wdp-sticky-section-no elementor-widget elementor-widget-heading"
              data-id="cdb3c1f" data-element_type="widget" data-widget_type="heading.default">
              <div class="elementor-widget-container">
                <h2 class="elementor-heading-title elementor-size-default"><a href="https://einvite.id">Pernikahan Dari
                    Khairil & Riska</a></h2>
              </div>
            </div>
          </div>
        </div>
        <div
          class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-1e84036 wdp-sticky-section-no"
          data-id="1e84036" data-element_type="column">
          <div class="elementor-widget-wrap elementor-element-populated">
            <div
              class="elementor-element elementor-element-f917195 wdp-sticky-section-no elementor-widget elementor-widget-bdt-qrcode"
              data-id="f917195" data-element_type="widget" data-widget_type="bdt-qrcode.default">
              <div class="elementor-widget-container">
                <div class="bdt-qrcode"
                  data-settings="{&quot;render&quot;:&quot;canvas&quot;,&quot;ecLevel&quot;:&quot;Q&quot;,&quot;minVersion&quot;:2,&quot;fill&quot;:&quot;#333333&quot;,&quot;size&quot;:150,&quot;mSize&quot;:11,&quot;mPosX&quot;:50,&quot;mPosY&quot;:50,&quot;background&quot;:&quot;transparent&quot;}">
                </div>
              </div>
            </div>
            <div
              class="elementor-element elementor-element-86bc060 wdp-sticky-section-no elementor-widget elementor-widget-heading"
              data-id="86bc060" data-element_type="widget" data-widget_type="heading.default">
              <div class="elementor-widget-container">
                <h2 class="elementor-heading-title elementor-size-default"><a href="https://einvite.id">Tamu
                    Undangan</a></h2>
              </div>
            </div>
            <div
              class="elementor-element elementor-element-ccadae5 wdp-sticky-section-no elementor-widget elementor-widget-heading"
              data-id="ccadae5" data-element_type="widget" data-widget_type="heading.default">
              <div class="elementor-widget-container">
                <h2 class="elementor-heading-title elementor-size-default">Tunjukkan QR kepada petugas penerima tamu
                </h2>
              </div>
            </div>
            <div
              class="elementor-element elementor-element-3cc3da3 wdp-sticky-section-no elementor-widget elementor-widget-image"
              data-id="3cc3da3" data-element_type="widget" data-widget_type="image.default">
              <div class="elementor-widget-container">
                <a href="https://einvite.id">
                  <img width="500" height="123"
                    src="https://i0.wp.com/einvite.id/wp-content/uploads/logoe.id_.png?fit=500%2C123&amp;ssl=1"
                    class="attachment-full size-full" alt loading="lazy"
                    srcset="https://i0.wp.com/einvite.id/wp-content/uploads/logoe.id_.png?w=500&amp;ssl=1 500w, https://i0.wp.com/einvite.id/wp-content/uploads/logoe.id_.png?resize=150%2C37&amp;ssl=1 150w"
                    sizes="(max-width: 500px) 100vw, 500px" /> </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section data-dce-background-color="#FFFFFF00"
      class="elementor-section elementor-top-section elementor-element elementor-element-424953e elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
      data-id="424953e" data-element_type="section"
      data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
      <div class="elementor-container elementor-column-gap-default">
        <div
          class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-8f839b2 wdp-sticky-section-no"
          data-id="8f839b2" data-element_type="column">
          <div class="elementor-widget-wrap elementor-element-populated">
            <div
              class="elementor-element elementor-element-1ce4ef1 wdp-sticky-section-no elementor-widget elementor-widget-html"
              data-id="1ce4ef1" data-element_type="widget" data-widget_type="html.default">
              <div class="elementor-widget-container">
                <script data-minify="1"
                  src="https://einvite.id/wp-content/cache/min/1/dist/html2canvas.min.js?ver=1683821879"></script>
                <script type="rocketlazyloadscript">
                  function spinredirect(e){event.preventDefault(),jQuery(e).css("opacity","1"),jQuery(e).css("pointer-events","none"),jQuery(e).find(".elementor-button-icon").html('<i class="fa fa-spinner fa-spin">'),jQuery(e).find(".elementor-button-text").html("Loading"),setTimeout(function(){window.location.href=jQuery(e).attr("href")},3e3)}jQuery(".download a").attr("onclick","spinredirect(this);");
</script>
                <script type="rocketlazyloadscript">
                  function saveAs(e,n){var o=document.createElement("a");"string"==typeof o.download?(o.href=e,o.download=n,document.body.appendChild(o),o.click(),document.body.removeChild(o)):window.open(e)}document.getElementById("download").addEventListener("click",function(){html2canvas(document.querySelector("#qrsection"),{useCORS:!0,scale:2}).then(function(e){saveAs(e.toDataURL(),"QR-einvite.id.png")})});
</script>
              </div>
            </div>
            <div data-dce-background-color="#000000"
              class="elementor-element elementor-element-106cf45 elementor-align-center download wdp-sticky-section-no elementor-widget elementor-widget-button"
              data-id="106cf45" data-element_type="widget" id="download" data-widget_type="button.default">
              <div class="elementor-widget-container">
                <div class="elementor-button-wrapper">
                  <a href="#qrsection" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                    <span class="elementor-button-content-wrapper">
                      <span class="elementor-button-icon elementor-align-icon-left">
                        <i aria-hidden="true" class="fas fa-download"></i> </span>
                      <span class="elementor-button-text">Download QR</span>
                    </span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> -->
  <!-- <div data-elementor-type="popup" data-elementor-id="22737" class="elementor elementor-22737 elementor-location-popup"
    data-elementor-settings="{&quot;triggers&quot;:[],&quot;timing&quot;:{&quot;page_views&quot;:&quot;yes&quot;,&quot;page_views_views&quot;:2,&quot;sessions&quot;:&quot;yes&quot;,&quot;devices&quot;:&quot;yes&quot;,&quot;browsers&quot;:&quot;yes&quot;,&quot;sessions_sessions&quot;:1,&quot;times_times&quot;:1,&quot;times&quot;:&quot;yes&quot;,&quot;devices_devices&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;browsers_browsers&quot;:&quot;all&quot;}}">
    <section
      class="elementor-section elementor-top-section elementor-element elementor-element-bef4ab4 elementor-section-full_width elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
      data-id="bef4ab4" data-element_type="section">
      <div class="elementor-container elementor-column-gap-no">
        <div
          class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-6e4baaf animated-slow wdp-sticky-section-no elementor-invisible"
          data-id="6e4baaf" data-element_type="column" data-settings="{&quot;animation&quot;:&quot;fadeIn&quot;}">
          <div class="elementor-widget-wrap elementor-element-populated">
            <div
              class="elementor-element elementor-element-2386edd wdp-sticky-section-no elementor-widget elementor-widget-heading"
              data-id="2386edd" data-element_type="widget" data-widget_type="heading.default">
              <div class="elementor-widget-container">
                <h2 class="elementor-heading-title elementor-size-default">Sudah siap untuk buat Undangan??</h2>
              </div>
            </div>
            <div
              class="elementor-element elementor-element-b42bc5f wdp-sticky-section-no elementor-widget elementor-widget-heading"
              data-id="b42bc5f" data-element_type="widget" data-widget_type="heading.default">
              <div class="elementor-widget-container">
                <p class="elementor-heading-title elementor-size-medium">Sebelum meninggalkan halaman ini, mohon kira
                  nya untuk follow kita di Instagram @einvite.id, atau simpan nomer Whatsapp kita untuk komunikasi di
                  kemudian hari<br>Terimakasih</p>
              </div>
            </div>
            <section
              class="elementor-section elementor-inner-section elementor-element elementor-element-5d3aabb elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no"
              data-id="5d3aabb" data-element_type="section">
              <div class="elementor-container elementor-column-gap-default">
                <div
                  class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-01cecbd wdp-sticky-section-no"
                  data-id="01cecbd" data-element_type="column">
                  <div class="elementor-widget-wrap elementor-element-populated">
                    <div data-dce-background-color="#FFFFFF"
                      class="elementor-element elementor-element-256f73d elementor-align-center wdp-sticky-section-no elementor-widget elementor-widget-button"
                      data-id="256f73d" data-element_type="widget" data-widget_type="button.default">
                      <div class="elementor-widget-container">
                        <div class="elementor-button-wrapper">
                          <a href="https://www.instagram.com/einvite.id/" target="_blank"
                            class="elementor-button-link elementor-button elementor-size-md elementor-animation-pulse"
                            role="button">
                            <span class="elementor-button-content-wrapper">
                              <span class="elementor-button-icon elementor-align-icon-left">
                                <i aria-hidden="true" class="fab fa-instagram"></i> </span>
                              <span class="elementor-button-text">Follow Instagram</span>
                            </span>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-94fbe44 wdp-sticky-section-no"
                  data-id="94fbe44" data-element_type="column">
                  <div class="elementor-widget-wrap elementor-element-populated">
                    <div data-dce-background-color="#FFFFFF"
                      class="elementor-element elementor-element-43d8578 elementor-align-center wdp-sticky-section-no elementor-widget elementor-widget-button"
                      data-id="43d8578" data-element_type="widget" data-widget_type="button.default">
                      <div class="elementor-widget-container">
                        <div class="elementor-button-wrapper">
                          <a href="https://wa.me/message/2KGM7HZ3QPEZP1" target="_blank"
                            class="elementor-button-link elementor-button elementor-size-md elementor-animation-pulse"
                            role="button">
                            <span class="elementor-button-content-wrapper">
                              <span class="elementor-button-icon elementor-align-icon-left">
                                <i aria-hidden="true" class="fab fa-whatsapp"></i> </span>
                              <span class="elementor-button-text">Hubungi Kami</span>
                            </span>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <div
              class="elementor-element elementor-element-8a3a8a2 wdp-sticky-section-no elementor-widget elementor-widget-image"
              data-id="8a3a8a2" data-element_type="widget" data-widget_type="image.default">
              <div class="elementor-widget-container">
                <a href="https://einvite.id" target="_blank">
                  <img width="200" height="49"
                    src="https://i0.wp.com/einvite.id/wp-content/uploads/2020/12/logoeinvite.png?fit=200%2C49&amp;ssl=1"
                    class="elementor-animation-pulse attachment-full size-full" alt loading="lazy" /> </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> -->
  <script type="rocketlazyloadscript" data-rocket-type="text/javascript">
    (function () {
			var c = document.body.className;
			c = c.replace(/woocommerce-no-js/, 'woocommerce-js');
			document.body.className = c;
		})();
	</script>
  <link rel="preload" href="css/e-gallery.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/tooltip.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link data-minify="1" rel="preload" href="https://einvite.id/wp-content/cache/min/1/wp-content/uploads/elementor/css/post-30784.css?ver=1683821879" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <link rel="preload" href="css/animations.min.css" data-rocket-async="style" as="style" onload="this.onload=null;this.rel='stylesheet'" media="all" />
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/p/jetpack/11.9.1/_inc/build/photon/photon.min.js?ver=1683821879" id="jetpack-photon-js"></script>
  <script id="wapf-frontend-js-js-extra">
    var wapf_config = {
      "page_type": "other"
    };
  </script>
  <script src="js/a-frontend.min.js" id="wapf-frontend-js-js"></script>
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/p/woocommerce/7.5.1/assets/js/js-cookie/js.cookie.min.js?ver=1683821879" id="js-cookie-js"></script>
  <script id="wc-cart-fragments-js-extra">
    var wc_cart_fragments_params = {
      "ajax_url": "\/wp-admin\/admin-ajax.php",
      "wc_ajax_url": "\/?wc-ajax=%%endpoint%%",
      "cart_hash_key": "wc_cart_hash_3fc16c81c10c00d178d6ee91d61cd322",
      "fragment_name": "wc_fragments_3fc16c81c10c00d178d6ee91d61cd322",
      "request_timeout": "5000"
    };
  </script>
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/p/woocommerce/7.5.1/assets/js/frontend/cart-fragments.min.js?ver=1683821879" id="wc-cart-fragments-js"></script>
  <script id="wc-cart-fragments-js-after">
    jQuery('body').bind('wc_fragments_refreshed', function() {
      var jetpackLazyImagesLoadEvent;
      try {
        jetpackLazyImagesLoadEvent = new Event('jetpack-lazy-images-load', {
          bubbles: true,
          cancelable: true
        });
      } catch (e) {
        jetpackLazyImagesLoadEvent = document.createEvent('Event')
        jetpackLazyImagesLoadEvent.initEvent('jetpack-lazy-images-load', true, true);
      }
      jQuery('body').get(0).dispatchEvent(jetpackLazyImagesLoadEvent);
    });
  </script>
  <script src="js/wdp-swiper.min.js" id="wdp-swiper-js-js"></script>
  <script data-minify="1" src="js/qr-code.js" id="weddingpress-qr-js"></script>
  <script data-minify="1" src="js/wdp-horizontal.js" id="wdp-horizontal-js-js"></script>
  <script src="js/exad-scripts.min.js" id="exad-main-script-js"></script>
  <script id="wdp_js_script-js-extra">
    var WDP_WP = {
      "ajaxurl": "https:\/\/einvite.id\/wp-admin\/admin-ajax.php",
      "wdpNonce": "0635a3da02",
      "jpages": "true",
      "jPagesNum": "9",
      "textCounter": "true",
      "textCounterNum": "500",
      "widthWrap": "",
      "autoLoad": "true",
      "thanksComment": "Terima kasih atas ucapan & doanya!",
      "thanksReplyComment": "Terima kasih atas balasannya!",
      "duplicateComment": "You might have left one of the fields blank, or duplicate comments",
      "accept": "Accept",
      "cancel": "Cancel",
      "reply": "Balas",
      "textWriteComment": "Tulis Ucapan & Doa",
      "classPopularComment": "wdp-popular-comment",
      "textToDisplay": "Text to display",
      "textCharacteresMin": "Minimal 2 karakter",
      "textNavNext": "Selanjutnya",
      "textNavPrev": "Sebelumnya",
      "textMsgDeleteComment": "Do you want delete this comment?",
      "textLoadMore": "Load more"
    };
  </script>
  <script data-minify="1" src="js/wdp_script.js" id="wdp_js_script-js"></script>
  <script src="js/jquery.jPages.min.js" id="wdp_jPages-js"></script>
  <script data-minify="1" src="js/jquery.textareaCounter.js" id="wdp_textCounter-js"></script>
  <script src="js/jquery.placeholder.min.js" id="wdp_placeholder-js"></script>
  <script src="js/autosize.min.js" id="wdp_autosize-js"></script>
  <script id="rocket-browser-checker-js-after">
    "use strict";
    var _createClass = function() {
      function defineProperties(target, props) {
        for (var i = 0; i < props.length; i++) {
          var descriptor = props[i];
          descriptor.enumerable = descriptor.enumerable || !1, descriptor.configurable = !0, "value" in descriptor &&
            (descriptor.writable = !0), Object.defineProperty(target, descriptor.key, descriptor)
        }
      }
      return function(Constructor, protoProps, staticProps) {
        return protoProps && defineProperties(Constructor.prototype, protoProps), staticProps && defineProperties(
          Constructor, staticProps), Constructor
      }
    }();

    function _classCallCheck(instance, Constructor) {
      if (!(instance instanceof Constructor)) throw new TypeError("Cannot call a class as a function")
    }
    var RocketBrowserCompatibilityChecker = function() {
      function RocketBrowserCompatibilityChecker(options) {
        _classCallCheck(this, RocketBrowserCompatibilityChecker), this.passiveSupported = !1, this
          ._checkPassiveOption(this), this.options = !!this.passiveSupported && options
      }
      return _createClass(RocketBrowserCompatibilityChecker, [{
        key: "_checkPassiveOption",
        value: function(self) {
          try {
            var options = {
              get passive() {
                return !(self.passiveSupported = !0)
              }
            };
            window.addEventListener("test", null, options), window.removeEventListener("test", null, options)
          } catch (err) {
            self.passiveSupported = !1
          }
        }
      }, {
        key: "initRequestIdleCallback",
        value: function() {
          !1 in window && (window.requestIdleCallback = function(cb) {
            var start = Date.now();
            return setTimeout(function() {
              cb({
                didTimeout: !1,
                timeRemaining: function() {
                  return Math.max(0, 50 - (Date.now() - start))
                }
              })
            }, 1)
          }), !1 in window && (window.cancelIdleCallback = function(id) {
            return clearTimeout(id)
          })
        }
      }, {
        key: "isDataSaverModeOn",
        value: function() {
          return "connection" in navigator && !0 === navigator.connection.saveData
        }
      }, {
        key: "supportsLinkPrefetch",
        value: function() {
          var elem = document.createElement("link");
          return elem.relList && elem.relList.supports && elem.relList.supports("prefetch") && window
            .IntersectionObserver && "isIntersecting" in IntersectionObserverEntry.prototype
        }
      }, {
        key: "isSlowConnection",
        value: function() {
          return "connection" in navigator && "effectiveType" in navigator.connection && ("2g" === navigator
            .connection.effectiveType || "slow-2g" === navigator.connection.effectiveType)
        }
      }]), RocketBrowserCompatibilityChecker
    }();
  </script>
  <script id="rocket-preload-links-js-extra">
    var RocketPreloadLinksConfig = {
      "excludeUris": "\/data-undangan|\/eric-chandra-liem-so-hong\/|\/eric-hong-golden-anniversary\/|\/(.+\/)?feed\/?.+\/?|\/(?:.+\/)?embed\/|\/checkout\/|\/cart\/|\/account\/|\/wc-api\/v(.*)|\/(index\\.php\/)?wp\\-json(\/.*|$)|\/wp-admin\/|\/logout\/|\/wp-login.php",
      "usesTrailingSlash": "1",
      "imageExt": "jpg|jpeg|gif|png|tiff|bmp|webp|avif",
      "fileExt": "jpg|jpeg|gif|png|tiff|bmp|webp|avif|php|pdf|html|htm",
      "siteUrl": "https:\/\/einvite.id",
      "onHoverDelay": "100",
      "rateThrottle": "3"
    };
  </script>
  <script id="rocket-preload-links-js-after">
    (function() {
      "use strict";
      var r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
          return typeof e
        } : function(e) {
          return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" :
            typeof e
        },
        e = function() {
          function i(e, t) {
            for (var n = 0; n < t.length; n++) {
              var i = t[n];
              i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object
                .defineProperty(e, i.key, i)
            }
          }
          return function(e, t, n) {
            return t && i(e.prototype, t), n && i(e, n), e
          }
        }();

      function i(e, t) {
        if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
      }
      var t = function() {
        function n(e, t) {
          i(this, n), this.browser = e, this.config = t, this.options = this.browser.options, this.prefetched =
            new Set, this.eventTime = null, this.threshold = 1111, this.numOnHover = 0
        }
        return e(n, [{
          key: "init",
          value: function() {
            !this.browser.supportsLinkPrefetch() || this.browser.isDataSaverModeOn() || this.browser
              .isSlowConnection() || (this.regex = {
                excludeUris: RegExp(this.config.excludeUris, "i"),
                images: RegExp(".(" + this.config.imageExt + ")$", "i"),
                fileExt: RegExp(".(" + this.config.fileExt + ")$", "i")
              }, this._initListeners(this))
          }
        }, {
          key: "_initListeners",
          value: function(e) {
            -1 < this.config.onHoverDelay && document.addEventListener("mouseover", e.listener.bind(e), e
              .listenerOptions), document.addEventListener("mousedown", e.listener.bind(e), e
              .listenerOptions), document.addEventListener("touchstart", e.listener.bind(e), e
              .listenerOptions)
          }
        }, {
          key: "listener",
          value: function(e) {
            var t = e.target.closest("a"),
              n = this._prepareUrl(t);
            if (null !== n) switch (e.type) {
              case "mousedown":
              case "touchstart":
                this._addPrefetchLink(n);
                break;
              case "mouseover":
                this._earlyPrefetch(t, n, "mouseout")
            }
          }
        }, {
          key: "_earlyPrefetch",
          value: function(t, e, n) {
            var i = this,
              r = setTimeout(function() {
                if (r = null, 0 === i.numOnHover) setTimeout(function() {
                  return i.numOnHover = 0
                }, 1e3);
                else if (i.numOnHover > i.config.rateThrottle) return;
                i.numOnHover++, i._addPrefetchLink(e)
              }, this.config.onHoverDelay);
            t.addEventListener(n, function e() {
              t.removeEventListener(n, e, {
                passive: !0
              }), null !== r && (clearTimeout(r), r = null)
            }, {
              passive: !0
            })
          }
        }, {
          key: "_addPrefetchLink",
          value: function(i) {
            return this.prefetched.add(i.href), new Promise(function(e, t) {
              var n = document.createElement("link");
              n.rel = "prefetch", n.href = i.href, n.onload = e, n.onerror = t, document.head
                .appendChild(n)
            }).catch(function() {})
          }
        }, {
          key: "_prepareUrl",
          value: function(e) {
            if (null === e || "object" !== (void 0 === e ? "undefined" : r(e)) || !1 in e || -1 === [
                "http:", "https:"
              ].indexOf(e.protocol)) return null;
            var t = e.href.substring(0, this.config.siteUrl.length),
              n = this._getPathname(e.href, t),
              i = {
                original: e.href,
                protocol: e.protocol,
                origin: t,
                pathname: n,
                href: t + n
              };
            return this._isLinkOk(i) ? i : null
          }
        }, {
          key: "_getPathname",
          value: function(e, t) {
            var n = t ? e.substring(this.config.siteUrl.length) : e;
            return n.startsWith("/") || (n = "/" + n), this._shouldAddTrailingSlash(n) ? n + "/" : n
          }
        }, {
          key: "_shouldAddTrailingSlash",
          value: function(e) {
            return this.config.usesTrailingSlash && !e.endsWith("/") && !this.regex.fileExt.test(e)
          }
        }, {
          key: "_isLinkOk",
          value: function(e) {
            return null !== e && "object" === (void 0 === e ? "undefined" : r(e)) && (!this.prefetched.has(e
                .href) && e.origin === this.config.siteUrl && -1 === e.href.indexOf("?") && -1 === e.href
              .indexOf("#") && !this.regex.excludeUris.test(e.href) && !this.regex.images.test(e.href))
          }
        }], [{
          key: "run",
          value: function() {
            "undefined" != typeof RocketPreloadLinksConfig && new n(new RocketBrowserCompatibilityChecker({
              capture: !0,
              passive: !0
            }), RocketPreloadLinksConfig).init()
          }
        }]), n
      }();
      t.run();
    }());
  </script>
  <script id="eael-general-js-extra">
    var localize = {
      "ajaxurl": "https:\/\/einvite.id\/wp-admin\/admin-ajax.php",
      "nonce": "b98f62fccc",
      "i18n": {
        "added": "Added ",
        "compare": "Compare",
        "loading": "Loading..."
      },
      "eael_translate_text": {
        "required_text": "is a required field",
        "invalid_text": "Invalid",
        "billing_text": "Billing",
        "shipping_text": "Shipping",
        "fg_mfp_counter_text": "of"
      },
      "page_permalink": "https:\/\/einvite.id\/premium-17\/",
      "cart_redirectition": "yes",
      "cart_page_url": "https:\/\/einvite.id\/cart\/",
      "el_breakpoints": {
        "mobile": {
          "label": "Mobile",
          "value": 767,
          "default_value": 767,
          "direction": "max",
          "is_enabled": true
        },
        "mobile_extra": {
          "label": "Mobile Extra",
          "value": 880,
          "default_value": 880,
          "direction": "max",
          "is_enabled": false
        },
        "tablet": {
          "label": "Tablet",
          "value": 1024,
          "default_value": 1024,
          "direction": "max",
          "is_enabled": true
        },
        "tablet_extra": {
          "label": "Tablet Extra",
          "value": 1200,
          "default_value": 1200,
          "direction": "max",
          "is_enabled": false
        },
        "laptop": {
          "label": "Laptop",
          "value": 1366,
          "default_value": 1366,
          "direction": "max",
          "is_enabled": false
        },
        "widescreen": {
          "label": "Widescreen",
          "value": 2400,
          "default_value": 2400,
          "direction": "min",
          "is_enabled": false
        }
      }
    };
  </script>
  <script src="js/general.min.js" id="eael-general-js"></script>
  <script src="js/jquery-numerator.min.js" id="jquery-numerator-js"></script>
  <script src="js/e-gallery.min.js" id="elementor-gallery-js"></script>
  <script src="js/popper.min.js" id="dce-popper-js"></script>
  <script src="js/tippy-bundle.umd.min.js" id="dce-tippy-js"></script>
  <script src="js/tooltip.min.js" id="dce-tooltip-js"></script>
  <script src="js/jquery-qrcode.min.js" id="qrcode-js"></script>
  <script id="bdt-uikit-js-extra">
    var element_pack_ajax_login_config = {
      "ajaxurl": "https:\/\/einvite.id\/wp-admin\/admin-ajax.php",
      "language": "en",
      "loadingmessage": "Sending user info, please wait...",
      "unknownerror": "Unknown error, make sure access is correct!"
    };
    var ElementPackConfig = {
      "ajaxurl": "https:\/\/einvite.id\/wp-admin\/admin-ajax.php",
      "nonce": "e42c87acd2",
      "data_table": {
        "language": {
          "lengthMenu": "Show _MENU_ Entries",
          "info": "Showing _START_ to _END_ of _TOTAL_ entries",
          "search": "Search :",
          "sZeroRecords": "No matching records found",
          "paginate": {
            "previous": "Previous",
            "next": "Next"
          }
        }
      },
      "contact_form": {
        "sending_msg": "Sending message please wait...",
        "captcha_nd": "Invisible captcha not defined!",
        "captcha_nr": "Could not get invisible captcha response!"
      },
      "mailchimp": {
        "subscribing": "Subscribing you please wait..."
      },
      "search": {
        "more_result": "More Results",
        "search_result": "SEARCH RESULT",
        "not_found": "not found"
      },
      "elements_data": {
        "sections": [],
        "columns": [],
        "widgets": []
      }
    };
  </script>
  <script src="js/bdt-uikit.min.js" id="bdt-uikit-js"></script>
  <script src="js/webpack-pro.runtime.min.js" id="elementor-pro-webpack-runtime-js"></script>
  <script src="js/webpack.runtime.min.js" id="elementor-webpack-runtime-js"></script>
  <script src="js/frontend-modules.min.js" id="elementor-frontend-modules-js"></script>
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/js/dist/vendor/regenerator-runtime.min.js?ver=1683821879" id="regenerator-runtime-js"></script>
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/js/dist/vendor/wp-polyfill.min.js?ver=1683821879" id="wp-polyfill-js"></script>
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/js/dist/hooks.min.js?ver=1683821880" id="wp-hooks-js"></script>
  <script src="https://c0.wp.com/c/6.0.3/wp-includes/js/dist/i18n.min.js" id="wp-i18n-js"></script>
  <script id="wp-i18n-js-after">
    wp.i18n.setLocaleData({
      'text direction\u0004ltr': ['ltr']
    });
  </script>
  <script id="elementor-pro-frontend-js-before">
    var ElementorProFrontendConfig = {
      "ajaxurl": "https:\/\/einvite.id\/wp-admin\/admin-ajax.php",
      "nonce": "2de6dcfb8c",
      "urls": {
        "assets": "https:\/\/einvite.id\/wp-content\/plugins\/elementor-pro\/assets\/",
        "rest": "https:\/\/einvite.id\/wp-json\/"
      },
      "shareButtonsNetworks": {
        "facebook": {
          "title": "Facebook",
          "has_counter": true
        },
        "twitter": {
          "title": "Twitter"
        },
        "linkedin": {
          "title": "LinkedIn",
          "has_counter": true
        },
        "pinterest": {
          "title": "Pinterest",
          "has_counter": true
        },
        "reddit": {
          "title": "Reddit",
          "has_counter": true
        },
        "vk": {
          "title": "VK",
          "has_counter": true
        },
        "odnoklassniki": {
          "title": "OK",
          "has_counter": true
        },
        "tumblr": {
          "title": "Tumblr"
        },
        "digg": {
          "title": "Digg"
        },
        "skype": {
          "title": "Skype"
        },
        "stumbleupon": {
          "title": "StumbleUpon",
          "has_counter": true
        },
        "mix": {
          "title": "Mix"
        },
        "telegram": {
          "title": "Telegram"
        },
        "pocket": {
          "title": "Pocket",
          "has_counter": true
        },
        "xing": {
          "title": "XING",
          "has_counter": true
        },
        "whatsapp": {
          "title": "WhatsApp"
        },
        "email": {
          "title": "Email"
        },
        "print": {
          "title": "Print"
        }
      },
      "woocommerce": {
        "menu_cart": {
          "cart_page_url": "https:\/\/einvite.id\/cart\/",
          "checkout_page_url": "https:\/\/einvite.id\/checkout\/"
        }
      },
      "facebook_sdk": {
        "lang": "en_US",
        "app_id": ""
      },
      "lottie": {
        "defaultAnimationUrl": "https:\/\/einvite.id\/wp-content\/plugins\/elementor-pro\/modules\/lottie\/assets\/animations\/default.json"
      }
    };
  </script>
  <script src="js/frontend.min.js" id="elementor-pro-frontend-js"></script>
  <script src="js/waypoints.min.js" id="elementor-waypoints-js"></script>
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/js/jquery/ui/core.min.js?ver=1683821880" id="jquery-ui-core-js"></script>
  <script src="js/swiper.min.js" id="swiper-js"></script>
  <script src="js/share-link.min.js" id="share-link-js"></script>
  <script src="js/dialog.min.js" id="elementor-dialog-js"></script>
  <script id="elementor-frontend-js-before">
    var elementorFrontendConfig = {
      "environmentMode": {
        "edit": false,
        "wpPreview": false,
        "isScriptDebug": false
      },
      "i18n": {
        "shareOnFacebook": "Share on Facebook",
        "shareOnTwitter": "Share on Twitter",
        "pinIt": "Pin it",
        "download": "Download",
        "downloadImage": "Download image",
        "fullscreen": "Fullscreen",
        "zoom": "Zoom",
        "share": "Share",
        "playVideo": "Play Video",
        "previous": "Previous",
        "next": "Next",
        "close": "Close"
      },
      "is_rtl": false,
      "breakpoints": {
        "xs": 0,
        "sm": 480,
        "md": 768,
        "lg": 1025,
        "xl": 1440,
        "xxl": 1600
      },
      "responsive": {
        "breakpoints": {
          "mobile": {
            "label": "Mobile",
            "value": 767,
            "default_value": 767,
            "direction": "max",
            "is_enabled": true
          },
          "mobile_extra": {
            "label": "Mobile Extra",
            "value": 880,
            "default_value": 880,
            "direction": "max",
            "is_enabled": false
          },
          "tablet": {
            "label": "Tablet",
            "value": 1024,
            "default_value": 1024,
            "direction": "max",
            "is_enabled": true
          },
          "tablet_extra": {
            "label": "Tablet Extra",
            "value": 1200,
            "default_value": 1200,
            "direction": "max",
            "is_enabled": false
          },
          "laptop": {
            "label": "Laptop",
            "value": 1366,
            "default_value": 1366,
            "direction": "max",
            "is_enabled": false
          },
          "widescreen": {
            "label": "Widescreen",
            "value": 2400,
            "default_value": 2400,
            "direction": "min",
            "is_enabled": false
          }
        }
      },
      "version": "3.5.6",
      "is_static": false,
      "experimentalFeatures": {
        "e_dom_optimization": true,
        "a11y_improvements": true
      },
      "urls": {
        "assets": "https:\/\/einvite.id\/wp-content\/plugins\/elementor\/assets\/"
      },
      "settings": {
        "page": [],
        "editorPreferences": [],
        "dynamicooo": []
      },
      "kit": {
        "active_breakpoints": ["viewport_mobile", "viewport_tablet"],
        "global_image_lightbox": "yes",
        "lightbox_enable_fullscreen": "yes",
        "lightbox_enable_zoom": "yes",
        "woocommerce_notices_elements": []
      },
      "post": {
        "id": 64448,
        "title": "Premium%2017%20-%20Green%20White%20Gold%20Roses%20-%20einvite.id",
        "excerpt": "Tema Undangan Online Premium Green White Gold Roses untuk undangan online anda",
        "featuredImage": "https:\/\/i0.wp.com\/einvite.id\/wp-content\/uploads\/PP17-Green-White-Gold-Roses.jpg?fit=600%2C600&ssl=1"
      }
    };
  </script>
  <script src="js/frontend2.min.js" id="elementor-frontend-js"></script>
  <script src="js/preloaded-elements-handlers.min.js" id="pro-preloaded-elements-handlers-js"></script>
  <script src="js/preloaded-elements-handlers.min.js" id="preloaded-modules-js"></script>
  <script src="js/jquery.sticky.min.js" id="e-sticky-js"></script>
  <script data-minify="1" src="https://einvite.id/wp-content/cache/min/1/wp-content/uploads/element-pack/minified/js/ep-scripts.js?ver=1683821880" id="ep-scripts-js"></script>
  <script src="js/settings.min.js" id="dce-settings-js"></script>
  <script src="js/fix-background-loop.min.js" id="dce-fix-background-loop-js"></script>
  <script id="weddingpress-wdp-js-extra">
    var cevar = {
      "ajax_url": "https:\/\/einvite.id\/wp-admin\/admin-ajax.php",
      "plugin_url": "https:\/\/einvite.id\/wp-content\/plugins\/weddingpress\/"
    };
  </script>
  <script src="js/wdp.min.js" id="weddingpress-wdp-js"></script>
  <script src="js/guest-form.js" id="kirim-kit-js"></script>
  <script src="https://stats.wp.com/e-202319.js" defer></script>
  <script>
    _stq = window._stq || [];
    _stq.push(['view', {
      v: 'ext',
      blog: '186223195',
      post: '64448',
      tz: '8',
      srv: 'einvite.id',
      j: '1:11.9.1'
    }]);
    _stq.push(['clickTrackerInit', '186223195', '64448']);
  </script>
  <style>
    @media (max-width:767px) {
      .pafe-sticky-header-fixed-start-on-mobile {
        position: fixed !important;
        top: 0;
        width: 100%;
        z-index: 99;
      }

      .pafe-display-inline-block-mobile {
        display: inline-block;
        margin-bottom: 0;
        width: auto !important;
      }
    }

    @media (min-width:768px) and (max-width:1024px) {
      .pafe-sticky-header-fixed-start-on-tablet {
        position: fixed !important;
        top: 0;
        width: 100%;
        z-index: 99;
      }

      .pafe-display-inline-block-tablet {
        display: inline-block;
        margin-bottom: 0;
        width: auto !important;
      }
    }

    @media (min-width:1025px) {
      .pafe-sticky-header-fixed-start-on-desktop {
        position: fixed !important;
        top: 0;
        width: 100%;
        z-index: 99;
      }

      .pafe-display-inline-block-desktop {
        display: inline-block;
        margin-bottom: 0;
        width: auto !important;
      }
    }
  </style>
  <div class="pafe-break-point" data-pafe-break-point-md="768" data-pafe-break-point-lg="1025" data-pafe-ajax-url="https://einvite.id/wp-admin/admin-ajax.php"></div>
  <div data-pafe-form-builder-tinymce-upload="https://einvite.id/wp-content/plugins/piotnet-addons-for-elementor-pro/inc/tinymce/tinymce-upload.php">
  </div>
  <div data-pafe-plugin-url="https://einvite.id/wp-content/plugins"></div>
  <div data-pafe-ajax-url="https://einvite.id/wp-admin/admin-ajax.php"></div>
  <script type="rocketlazyloadscript">
    "use strict";var wprRemoveCPCSS=function wprRemoveCPCSS(){var elem;document.querySelector('link[data-rocket-async="style"][rel="preload"]')?setTimeout(wprRemoveCPCSS,200):(elem=document.getElementById("rocket-critical-css"))&&"remove"in elem&&elem.remove()};window.addEventListener?window.addEventListener("load",wprRemoveCPCSS):window.attachEvent&&window.attachEvent("onload",wprRemoveCPCSS);
  </script>
  <script>
    class RocketElementorAnimation {
      constructor() {
        this.deviceMode = document.createElement("span"), this.deviceMode.id = "elementor-device-mode", this
          .deviceMode.setAttribute("class", "elementor-screen-only"), document.body.appendChild(this.deviceMode)
      }
      _detectAnimations() {
        let t = getComputedStyle(this.deviceMode, ":after").content.replace(/"/g, "");
        this.animationSettingKeys = this._listAnimationSettingsKeys(t), document.querySelectorAll(
          ".elementor-invisible[data-settings]").forEach(t => {
          const e = t.getBoundingClientRect();
          if (e.bottom >= 0 && e.top <= window.innerHeight) try {
            this._animateElement(t)
          } catch (t) {}
        })
      }
      _animateElement(t) {
        const e = JSON.parse(t.dataset.settings),
          i = e._animation_delay || e.animation_delay || 0,
          n = e[this.animationSettingKeys.find(t => e[t])];
        if ("none" === n) return void t.classList.remove("elementor-invisible");
        t.classList.remove(n), this.currentAnimation && t.classList.remove(this.currentAnimation), this
          .currentAnimation = n;
        let s = setTimeout(() => {
          t.classList.remove("elementor-invisible"), t.classList.add("animated", n), this
            ._removeAnimationSettings(t, e)
        }, i);
        window.addEventListener("rocket-startLoading", function() {
          clearTimeout(s)
        })
      }
      _listAnimationSettingsKeys(t = "mobile") {
        const e = [""];
        switch (t) {
          case "mobile":
            e.unshift("_mobile");
          case "tablet":
            e.unshift("_tablet");
          case "desktop":
            e.unshift("_desktop")
        }
        const i = [];
        return ["animation", "_animation"].forEach(t => {
          e.forEach(e => {
            i.push(t + e)
          })
        }), i
      }
      _removeAnimationSettings(t, e) {
        this._listAnimationSettingsKeys().forEach(t => delete e[t]), t.dataset.settings = JSON.stringify(e)
      }
      static run() {
        const t = new RocketElementorAnimation;
        requestAnimationFrame(t._detectAnimations.bind(t))
      }
    }
    document.addEventListener("DOMContentLoaded", RocketElementorAnimation.run);
  </script><noscript>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCrimson%20Pro%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CGreat%20Vibes%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCroissant%20One%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMontserrat%20Alternates%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CEB%20Garamond%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Condensed%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CElsie%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CAbril%20Fatface%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CSacramento%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;display=swap" />
    <link data-minify="1" rel='stylesheet' id='bdt-uikit-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/bdthemes-element-pack/assets/css/bdt-uikit.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='ep-helper-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/bdthemes-element-pack/assets/css/ep-helper.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='mediaelement-css' href='https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/js/mediaelement/mediaelementplayer-legacy.min.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='wp-mediaelement-css' href='https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/js/mediaelement/wp-mediaelement.min.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='wc-blocks-vendors-style-css' href='https://einvite.id/wp-content/cache/min/1/p/woocommerce/7.5.1/packages/woocommerce-blocks/build/wc-blocks-vendors-style.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='wc-blocks-style-css' href='https://einvite.id/wp-content/cache/min/1/p/woocommerce/7.5.1/packages/woocommerce-blocks/build/wc-blocks-style.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='wapf-frontend-css-css' href='https://einvite.id/wp-content/plugins/advanced-product-fields-for-woocommerce/assets/css/frontend.min.css' media='all' />
    <link rel='stylesheet' id='cloudflare-edge-cache-css' href='https://einvite.id/wp-content/plugins/edge-cache-html-cloudflare-workers/public/css/cloudflare-edge-cache-public.css' media='all' />
    <link rel='stylesheet' id='pafe-extension-style-css' href='https://einvite.id/wp-content/plugins/piotnet-addons-for-elementor-pro/assets/css/minify/extension.min.css' media='all' />
    <link rel='stylesheet' id='pafe-woocommerce-sales-funnels-style-css' href='https://einvite.id/wp-content/plugins/piotnet-addons-for-elementor-pro/assets/css/minify/woocommerce-sales-funnels.min.css' media='all' />
    <link rel='stylesheet' id='pafe-extension-style-free-css' href='https://einvite.id/wp-content/plugins/piotnet-addons-for-elementor/assets/css/minify/extension.min.css' media='all' />
    <link rel='stylesheet' id='affwp-forms-css' href='https://einvite.id/wp-content/plugins/affiliate-wp/assets/css/forms.min.css' media='all' />
    <link rel='stylesheet' id='wdp-centered-css-css' href='https://einvite.id/wp-content/plugins/weddingpress/assets/css/wdp-centered-timeline.min.css' media='all' />
    <link rel='stylesheet' id='wdp-horizontal-css-css' href='https://einvite.id/wp-content/plugins/weddingpress/assets/css/wdp-horizontal-styles.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='wdp-fontello-css-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/weddingpress/assets/css/wdp-fontello.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='exad-main-style-css' href='https://einvite.id/wp-content/plugins/weddingpress/assets/css/exad-styles.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='edd-cr-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/weddingpress/assets/css/cr.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='wdp_style-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/weddingpress/addons/comment-kit/css/wdp_style.css?ver=1683821879' media='screen' />
    <link rel='stylesheet' id='hello-elementor-css' href='https://einvite.id/wp-content/themes/hello-elementor/style.min.css' media='all' />
    <link rel='stylesheet' id='hello-elementor-theme-style-css' href='https://einvite.id/wp-content/themes/hello-elementor/theme.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-icons-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/elementor/assets/lib/eicons/css/elementor-icons.min.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='elementor-frontend-css' href='https://einvite.id/wp-content/plugins/elementor/assets/css/frontend.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-post-1016-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/uploads/elementor/css/post-1016.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='powerpack-frontend-css' href='https://einvite.id/wp-content/plugins/powerpack-elements/assets/css/min/frontend.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='ep-styles-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/uploads/element-pack/minified/css/ep-styles.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='dce-style-css' href='https://einvite.id/wp-content/plugins/dynamic-content-for-elementor/assets/css/style.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='dashicons-css' href='https://einvite.id/wp-content/cache/min/1/c/6.0.3/wp-includes/css/dashicons.min.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='elementor-pro-css' href='https://einvite.id/wp-content/plugins/elementor-pro/assets/css/frontend.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='weddingpress-wdp-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/weddingpress/assets/css/wdp.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='kirim-kit-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/weddingpress/assets/css/guest-book.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='font-awesome-5-all-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/elementor/assets/lib/font-awesome/css/all.min.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='font-awesome-4-shim-css' href='https://einvite.id/wp-content/plugins/elementor/assets/lib/font-awesome/css/v4-shims.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-global-css' href='css/global.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-post-64448-css' href='css/post-64448.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-post-22737-css' href='css/post2.css' media='all' />
    <link rel='stylesheet' id='eael-general-css' href='https://einvite.id/wp-content/plugins/essential-addons-for-elementor-lite/assets/front-end/css/view/general.min.css' media='all' />
    <link rel='stylesheet' id='elementor-icons-shared-0-css' href='https://einvite.id/wp-content/plugins/elementor/assets/lib/font-awesome/css/fontawesome.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-icons-fa-brands-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/elementor/assets/lib/font-awesome/css/brands.min.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-icons-fa-solid-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/elementor/assets/lib/font-awesome/css/solid.min.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-icons-fa-regular-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/plugins/elementor/assets/lib/font-awesome/css/regular.min.css?ver=1683821879' media='all' />
    <link data-minify="1" rel='stylesheet' id='jetpack_css-css' href='https://einvite.id/wp-content/cache/min/1/p/jetpack/11.9.1/css/jetpack.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='elementor-gallery-css' href='https://einvite.id/wp-content/plugins/elementor/assets/lib/e-gallery/css/e-gallery.min.css' media='all' />
    <link rel='stylesheet' id='dce-tooltip-css' href='https://einvite.id/wp-content/plugins/dynamic-content-for-elementor/assets/css/tooltip.min.css' media='all' />
    <link data-minify="1" rel='stylesheet' id='elementor-post-30784-css' href='https://einvite.id/wp-content/cache/min/1/wp-content/uploads/elementor/css/post-30784.css?ver=1683821879' media='all' />
    <link rel='stylesheet' id='e-animations-css' href='https://einvite.id/wp-content/plugins/elementor/assets/lib/animations/animations.min.css' media='all' />
  </noscript>
</body>

</html>