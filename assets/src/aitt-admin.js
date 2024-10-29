// Hello? What's all this jQuery? We'll have no libraries here!
// Are you vanilla? This is a vanilla file, for vanilla people! There's nothing for jQuery folk here!
(function() {
  "use strict";

  // Abort if we're not on the dashboard.
  if (!document.getElementById("aitt")) {
    return;
  }

  const action = aitt_js_data.action;
  const nonce = aitt_js_data.nonce;
  const headers = new Headers({
    "Content-Type": "application/x-www-form-urlencoded"
  });
  const credentials = "same-origin";
  const method = "POST";

  const newTitleEl = document.getElementById("js-aitt-add-titles--text-field");
  const privateTitleEl = document.getElementById(
    "js-aitt-add-titles--checkbox-field"
  );
  const submitTitleEl = document.getElementById("js-aitt-add-titles--submit");
  const errorMessageEl = document.getElementById(
    "js-aitt-add-titles--msg-error"
  );
  const successMessageEl = document.getElementById(
    "js-aitt-add-titles--msg-success"
  );
  const titlesContainerEl = document.getElementById("js-aitt-list-titles");
  const titlesListEl = document.getElementById("js-aitt-list-titles--list");

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
    successMessageEl.textContent = message;

    // Replace the container with the latest titles.
    titlesListEl.innerHTML = titles;

    // Toggle titles container visibility.
    titlesContainerEl.style.display = titles ? "block" : "none";
  }

  /**
   * Listen for add title requests.
   */
  submitTitleEl.addEventListener("click", el => {
    // Get our form values.
    const titleVal = newTitleEl.value;
    const privateVal = privateTitleEl.checked;

    // Proceed if we have a title, otherwise,
    // show an error message.
    if (titleVal) {
      // Hide any messages.
      errorMessageEl.style.display = "none";
      successMessageEl.style.display = "none";

      // Build AJAX body.
      const ajaxBody = {
        action: action,
        wpnonce: nonce,
        type: "add",
        title: titleVal,
        private: privateVal,
        timestamp: Date.now()
      };

      // Build a proper query string based on the body parameters.
      const bodyString = Object.keys(ajaxBody)
        .map(key => key + "=" + ajaxBody[key])
        .join("&");

      // AJAX request.
      fetch(ajaxurl, {
        headers: headers,
        credentials: credentials,
        method: method,
        body: bodyString
      })
        .then(response => response.json())
        .then(response => {
          // Reset form fields.
          newTitleEl.value = "";
          privateTitleEl.checked = false;

          // Update the DOM.
          updateDOM(response.data.message, JSON.parse(response.data.titles));
        });
    } else {
      // Hide any messages.
      successMessageEl.style.display = "none";
      errorMessageEl.style.display = "none";

      // Update and display the error message.
      errorMessageEl.textContent = "Please enter a valid post title!";
      errorMessageEl.style.display = "block";
    }
  });

  /**
   * Listen for remove title or create post requests.
   */
  titlesListEl.addEventListener("click", el => {
    // Proceed if the click was on a create or delete button.
    if (
      el.target.matches(".aitt-list-titles--create-post") ||
      el.target.matches(".aitt-list-titles--delete-title")
    ) {
      const actionType = el.target.matches(".aitt-list-titles--create-post")
        ? "create"
        : "remove";
      const parentEl = el.target.parentNode;
      const titleEl = parentEl.querySelectorAll(".title");
      const privateAttr = parentEl.getAttribute("data-private") || false;

      // Build AJAX body.
      const ajaxBody = {
        action: action,
        wpnonce: nonce,
        type: actionType,
        title: titleEl[0].textContent,
        private: privateAttr
      };

      // Build a proper query string based on the body parameters.
      const bodyString = Object.keys(ajaxBody)
        .map(key => key + "=" + ajaxBody[key])
        .join("&");

      // AJAX request.
      fetch(ajaxurl, {
        headers: headers,
        credentials: credentials,
        method: method,
        body: bodyString
      })
        .then(response => response.json())
        .then(response => {
          // Update the DOM.
          updateDOM(response.data.message, JSON.parse(response.data.titles));

          // Redirect to the edit post screen if we have created a post.
          if ("create" === actionType) {
            window.location = response.data.redirect_url.replace("#038;", "&");
          }
        });
    }
  });
})();
