<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="/tour/assets/img/web-logo.png" />
    <link rel="stylesheet" href="/tour/public/css/general.css" />
    <link rel="stylesheet" href="/tour/public/css/admin-panel.css" />
    <title>Travel App - Discover places</title>
  </head>
  <body>
    <header>
      <nav>
        <p><span>Tour</span> <strong>App</strong></p>
        <ul class="list__navigations">
          <li><a href="#">Destinations</a></li>
          <li><a href="#">Events</a></li>
          <li><a href="#">Bookmarks</a></li>
        </ul>
        <!-- <button class="btn__img--name">
          <span class="btn__img">
            <object
              type="image/svg+xml"
              data="/tour/assets/img/profile-circle-svgrepo-com.svg"
            >
              Your browser does not support SVG.
            </object>
          </span>
          <span>Sign in </span> 
        </button> -->
      </nav>
    </header>
    <main>
      <div class="search__container">
        <div class="search__container-inputs">
          <input
            type="text"
            class="search search__places"
            placeholder="Search for places"
          />
          <input
            type="text"
            class="search search__events"
            placeholder="Search for events"
          />
        </div>
        <button class="search-button">
          <object type="image/svg+xml" data="/tour/assets/img/search.svg">
            Your browser does not support SVG.
          </object>
        </button>
        <!-- <div id="error-message" class="error-message">No places found</div> -->
      </div>
      <div class="sidebar">
        <div class="profile">
          <img
            src="/tour/assets/img/img-africa/ethiopia/marc-szeglat-Rj21YHC1CIY-unsplash.jpg"
            alt="Profile Picture"
            class="profile-picture"
          />
          <p class="profile-name">John Doe</p>
        </div>
        <nav class="nav-menu">
          <ul>
            <li><a href="">Account Settings</a></li>
            <li><a href="/tour/views/guide/guideDestinations">My destinations</a></li>
            <li><a href="/tour/views/guide/addDestination">Create a Destination</a></li>
            <!-- <li><a href="/">Update Destination</a></li> -->
          </ul>
        </nav>
      </div>
      <!-- <div class="content">
        <h1>Welcome to the Main Content Area</h1>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </p>
        Add more content here to make the page scrollable -->
      </div> 
    </main>
  </body>
</html>
