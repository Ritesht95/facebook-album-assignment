Facebook Assignment for rtCamp Solutions Private Limited. (Placement Process)

Challenge-2: Facebook-Photos Challenge

    Create a small PHP-script to accomplish following parts:

    Part-1: Album Slideshow

        User visits your script page
        User will be asked to connect using his FB account
        Once authenticated, your script will pull his album list from FB
        User will click on an album name/thumbnail
        A pure CSS and Plain JS slideshow will start showing photos in that album (in full-screen mode)

    Part-2: Download Album

        Beside every album icon (step #4 in part-1), add a new icon/button saying “Download This Album”
        When the user clicks on that button, your script will fetch all photos in that album behind the scene and zip them inside a folder on server.
        You may start a “progress bar” as soon as user-click download button as download process may take time.
        Once zip building on server completes, show user link to zip file.
        When user clicks zip-file link, it will download zip folder without opening any new page.
        Beside album names, add a checkbox. Then add a common “Download Selected Album” button. This button will download selected albums into a common zip that will contain one folder for each album. Folder-name will be album-name.
        Also, add a big “Download All” button. This button will download all albums in a zip, similar to above.

    Part-3: Backup albums to Google Drive

        Provide the user with an option to move albums to a Google Drive.
            The Google Drive will contain a master folder whose name will be of the format facebook_<username>_albums where username will be the Facebook username of the user.
            The user’s Facebook albums will be backed up in this master folder. Photos from each album will go inside their respective folders. Folder names will be the same as the Facebook album names.
        To improve the user experience, include the three following buttons:
            “Move” button- This button will appear under each album on your website. When clicked, the corresponding album only will be moved to Google Drive
            “Move Selected”- This button will work along with a checkbox system. The user can select a few albums via checkboxes and click on this button. Only the selected albums will be moved to Google Drive
            “Move All”- This button will immediately move all user albums to Google Drive within their respective folders.
        Make sure that the user is asked to connect to their Google account only once, no matter how many times they choose to move data.

    Note – Before submitting your challenge for review, please add Facebook profile with username ‘rtc.test‘ as tester while your app is in development mode. This will expedite the review process.

Link to the Live Project: https://fbalbumrtcamp.000webhostapp.com/
Github Project Link: https://github.com/Ritesht95/facebook-album-assignment

User guide

    1. On the home page, The login button will allow to login using facebook and on successful authentication, user will be redirected to album list page.
    2. On album list page, clicking on name of album will open fullscreen carousel of that album's photos.
    3. There are two buttons under album's name, which are: move to drive and download buttons respectively from left to right.
    4. To download or to move all albums to drive there are two buttons there under the main title "Your albums" on the same page.
    5. To perform these operations on selected albums, user need to select albums using the checkbox on each of the album-box, which will pop-up two buttons on the bottom-right corner of the screen.
    6. User will be asked to login using his/her google account in order to move albums to drive.
    7. To logout from his/her account, there is a "Logout" option in dropdown on top-right corner of the page, which will terminate the session of user and redirect him/her to login page.

External Libraries used

    - Facebook PHP SDK v5: http://developers.facebook.com
    - Google drive PHP client: http://developers.google.com
    - Bootstrap : http://www.getbootstrap.com
    - Jquery : http://www.jquery.com
    - Font-Awesome: https://www.fontawesome.com
