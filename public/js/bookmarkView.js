let bookmarkList = [];

if (localStorage.getItem("Bookmarks") != null) {
  bookmarkList = JSON.parse(localStorage.getItem("Bookmarks"));
  displayBookmarks(bookmarkList);
}
function displayBookmarks(bookmarkList) {
  let markup = ``;
  if (bookmarkList.length == 0) markup = `<p>Wow such empty...</p>`;
  for (let i = 0; i < bookmarkList.length; i++) {
    markup += `
                <div class="img-rate-name-flx">
            <div>
              <img
                src="${bookmarkList[i].img}"
                alt="image"
              />
            </div>
            <div class="name-rating">
              <p class="place-name">${bookmarkList[i].siteName}</p>
              <p>${bookmarkList[i].rating}</p>
            </div>
          </div>
          <div class="bm-btns">
            <button class="btn btn-outline-main" onclick="visitWebsite(${i});">
              Visit
            </button>
            <button
              class="btn btn-outline-main"
              onclick="deleteBookmark(${i});"
            >Delete
            </button>
          </div>
    `;
  }

  document.querySelector(".bookmark__container").innerHTML = markup;
}

function visitWebsite(i) {
  window.open(bookmarkList[i].siteURL);
}

function deleteBookmark(i) {
  bookmarkList.splice(i, 1);
  localStorage.setItem("Bookmarks", JSON.stringify(bookmarkList));
  displayBookmarks(bookmarkList);
}
