const btn_click = document.getElementById("details__rating--icon-id");
const checkdisplay = document.querySelector(".bookmark-this-place");
const bm_img = document.querySelector("#details__rating--icon-id img");

let bookmarkName = window.location.search;
let bookmarkURL = window.location.href;
let imgURL = document.querySelector(".gallery__img--first");
let img = "";
// if fetched from the database its gonna be added
// havenet been included in addBookmark function
let rating = "";
let isBookmarked = false;

let bookmarkList = [];

if (localStorage.getItem("Bookmarks") != null) {
  bookmarkList = JSON.parse(localStorage.getItem("Bookmarks"));
  // displayBookmarks(bookmarkList);
}

bookmarkList.forEach((obj) => {
  Object.keys(obj).forEach((key) => {
    if (obj[key] === bookmarkURL) isBookmarked = true;
    // console.log(`${typeof obj[key]}`);
  });
});

if (isBookmarked) {
  btn_click.classList.add("clicked");
  checkdisplay.innerHTML = `&check; place has been bookmarked.`;
  bm_img.src = "/tour/assets/img/icons/bookmark.svg";
}

function addBookmark() {
  let bookmark = {
    siteName: bookmarkName,
    siteURL: bookmarkURL,
    img: imgURL ? imgURL.src : "",
  };
  bookmarkList.push(bookmark);
  localStorage.setItem("Bookmarks", JSON.stringify(bookmarkList));
  // displayBookmarks(bookmarkList);
  clearForm();
  console.log(bookmarkList);
}

function clearForm() {
  bookmarkName = "";
  bookmarkURL = "";
  img = "";
}

// function visitWebsite(i) {
//   window.open(bookmarkList[i].siteURL);
// }

// function deleteBookmark(i) {
//   bookmarkList.splice(i, 1);
//   localStorage.setItem("Bookmarks", JSON.stringify(bookmarkList));
//   // displayBookmarks(bookmarkList);
// }

// to check for bookmark button is clicked
btn_click.addEventListener("click", () => {
  if (!btn_click.classList.contains("clicked")) {
    // checkdisplay.innerHTML = `&check; place has been bookmarked.`;
    // bm_img.src = "/tour/assets/img/icons/bookmark.svg";
    // } else {
    // checkdisplay.innerHTML = "&larr; Bookmark this place ";
    btn_click.classList.add("clicked");
    checkdisplay.innerHTML = `&check; place has been bookmarked.`;
    bm_img.src = "/tour/assets/img/icons/bookmark.svg";
    addBookmark();
  }
});
