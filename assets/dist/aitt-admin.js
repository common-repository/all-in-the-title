// modules are defined as an array
// [ module function, map of requires ]
//
// map of requires is short require name -> numeric require
//
// anything defined in a previous bundle is accessed via the
// orig method which is the require for previous bundles
parcelRequire = (function (modules, cache, entry, globalName) {
  // Save the require from previous bundle to this closure if any
  var previousRequire = typeof parcelRequire === 'function' && parcelRequire;
  var nodeRequire = typeof require === 'function' && require;

  function newRequire(name, jumped) {
    if (!cache[name]) {
      if (!modules[name]) {
        // if we cannot find the module within our internal map or
        // cache jump to the current global require ie. the last bundle
        // that was added to the page.
        var currentRequire = typeof parcelRequire === 'function' && parcelRequire;
        if (!jumped && currentRequire) {
          return currentRequire(name, true);
        }

        // If there are other bundles on this page the require from the
        // previous one is saved to 'previousRequire'. Repeat this as
        // many times as there are bundles until the module is found or
        // we exhaust the require chain.
        if (previousRequire) {
          return previousRequire(name, true);
        }

        // Try the node require function if it exists.
        if (nodeRequire && typeof name === 'string') {
          return nodeRequire(name);
        }

        var err = new Error('Cannot find module \'' + name + '\'');
        err.code = 'MODULE_NOT_FOUND';
        throw err;
      }

      localRequire.resolve = resolve;
      localRequire.cache = {};

      var module = cache[name] = new newRequire.Module(name);

      modules[name][0].call(module.exports, localRequire, module, module.exports, this);
    }

    return cache[name].exports;

    function localRequire(x){
      return newRequire(localRequire.resolve(x));
    }

    function resolve(x){
      return modules[name][1][x] || x;
    }
  }

  function Module(moduleName) {
    this.id = moduleName;
    this.bundle = newRequire;
    this.exports = {};
  }

  newRequire.isParcelRequire = true;
  newRequire.Module = Module;
  newRequire.modules = modules;
  newRequire.cache = cache;
  newRequire.parent = previousRequire;
  newRequire.register = function (id, exports) {
    modules[id] = [function (require, module) {
      module.exports = exports;
    }, {}];
  };

  var error;
  for (var i = 0; i < entry.length; i++) {
    try {
      newRequire(entry[i]);
    } catch (e) {
      // Save first error but execute all entries
      if (!error) {
        error = e;
      }
    }
  }

  if (entry.length) {
    // Expose entry point to Node, AMD or browser globals
    // Based on https://github.com/ForbesLindesay/umd/blob/master/template.js
    var mainExports = newRequire(entry[entry.length - 1]);

    // CommonJS
    if (typeof exports === "object" && typeof module !== "undefined") {
      module.exports = mainExports;

    // RequireJS
    } else if (typeof define === "function" && define.amd) {
     define(function () {
       return mainExports;
     });

    // <script>
    } else if (globalName) {
      this[globalName] = mainExports;
    }
  }

  // Override the current require with this new one
  parcelRequire = newRequire;

  if (error) {
    // throw error from earlier, _after updating parcelRequire_
    throw error;
  }

  return newRequire;
})({"aitt-admin.js":[function(require,module,exports) {
// Hello? What's all this jQuery? We'll have no libraries here!
// Are you vanilla? This is a vanilla file, for vanilla people! There's nothing for jQuery folk here!
(function () {
  "use strict"; // Abort if we're not on the dashboard.

  if (!document.getElementById("aitt")) {
    return;
  }

  var action = aitt_js_data.action;
  var nonce = aitt_js_data.nonce;
  var headers = new Headers({
    "Content-Type": "application/x-www-form-urlencoded"
  });
  var credentials = "same-origin";
  var method = "POST";
  var newTitleEl = document.getElementById("js-aitt-add-titles--text-field");
  var privateTitleEl = document.getElementById("js-aitt-add-titles--checkbox-field");
  var submitTitleEl = document.getElementById("js-aitt-add-titles--submit");
  var errorMessageEl = document.getElementById("js-aitt-add-titles--msg-error");
  var successMessageEl = document.getElementById("js-aitt-add-titles--msg-success");
  var titlesContainerEl = document.getElementById("js-aitt-list-titles");
  var titlesListEl = document.getElementById("js-aitt-list-titles--list");
  /**
   * Update the messages and title list in the DOM.
   *
   * @param  {string} message Success message text.
   * @param  {string} titles  HTML string.
   *
   * @return {void}
   */

  function updateDOM(message, titles) {
    // Add the success message and make it visible.
    successMessageEl.textContent = message; // Replace the container with the latest titles.

    titlesListEl.innerHTML = titles; // Toggle titles container visibility.

    titlesContainerEl.style.display = titles ? "block" : "none";
  }
  /**
   * Listen for add title requests.
   */


  submitTitleEl.addEventListener("click", function (el) {
    // Get our form values.
    var titleVal = newTitleEl.value;
    var privateVal = privateTitleEl.checked; // Proceed if we have a title, otherwise,
    // show an error message.

    if (titleVal) {
      // Hide any messages.
      errorMessageEl.style.display = "none";
      successMessageEl.style.display = "none"; // Build AJAX body.

      var ajaxBody = {
        action: action,
        wpnonce: nonce,
        type: "add",
        title: titleVal,
        private: privateVal,
        timestamp: Date.now()
      }; // Build a proper query string based on the body parameters.

      var bodyString = Object.keys(ajaxBody).map(function (key) {
        return key + "=" + ajaxBody[key];
      }).join("&"); // AJAX request.

      fetch(ajaxurl, {
        headers: headers,
        credentials: credentials,
        method: method,
        body: bodyString
      }).then(function (response) {
        return response.json();
      }).then(function (response) {
        // Reset form fields.
        newTitleEl.value = "";
        privateTitleEl.checked = false; // Update the DOM.

        updateDOM(response.data.message, JSON.parse(response.data.titles));
      });
    } else {
      // Hide any messages.
      successMessageEl.style.display = "none";
      errorMessageEl.style.display = "none"; // Update and display the error message.

      errorMessageEl.textContent = "Please enter a valid post title!";
      errorMessageEl.style.display = "block";
    }
  });
  /**
   * Listen for remove title or create post requests.
   */

  titlesListEl.addEventListener("click", function (el) {
    // Proceed if the click was on a create or delete button.
    if (el.target.matches(".aitt-list-titles--create-post") || el.target.matches(".aitt-list-titles--delete-title")) {
      var actionType = el.target.matches(".aitt-list-titles--create-post") ? "create" : "remove";
      var parentEl = el.target.parentNode;
      var titleEl = parentEl.querySelectorAll(".title");
      var privateAttr = parentEl.getAttribute("data-private") || false; // Build AJAX body.

      var ajaxBody = {
        action: action,
        wpnonce: nonce,
        type: actionType,
        title: titleEl[0].textContent,
        private: privateAttr
      }; // Build a proper query string based on the body parameters.

      var bodyString = Object.keys(ajaxBody).map(function (key) {
        return key + "=" + ajaxBody[key];
      }).join("&"); // AJAX request.

      fetch(ajaxurl, {
        headers: headers,
        credentials: credentials,
        method: method,
        body: bodyString
      }).then(function (response) {
        return response.json();
      }).then(function (response) {
        // Update the DOM.
        updateDOM(response.data.message, JSON.parse(response.data.titles)); // Redirect to the edit post screen if we have created a post.

        if ("create" === actionType) {
          window.location = response.data.redirect_url.replace("#038;", "&");
        }
      });
    }
  });
})();
},{}]},{},["aitt-admin.js"], null)
//# sourceMappingURL=/aitt-admin.js.map