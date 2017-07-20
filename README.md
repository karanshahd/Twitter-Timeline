# Twitter-Timeline
An example of using the Twiter's OAuth aPI for performing login activity and viewing your tweets as well as your folllowers' tweets.

This script performs the following acions.

Part-1: User Timeline
1.Start => User visits your script page.
2.User will be asked to connect using his Twitter account using Twitter Auth.
3.After authentication, your script will pull latest 10 tweets from his “home” timeline.
4.10 tweets will be displayed using a jQuery-slideshow.


Part-2: Followers Timeline
1.Below jQuery-slideshow (in step#4 from part-1), display list 10 followers (you can take any 10 random followers).
2.Also, display a search followers box. Add auto-suggest support. That means as soon as user starts typing, his followers will start showing up.
3.When user will click on a follower name, 10 tweets from that follower’s user-timeline will be displayed in same jQuery-slider, without page refresh (use AJAX).

Part-3: Download Tweets
1.There will be a download button to download all tweets for logged in user.
2.Download can be performed in one of the following formats i.e. You choose the format you want. It would act as an advantage if you give the option to download the tweets in all the following formats:
csv, xml and json formats.
3.Once user clicks download button (after choosing option) all tweets for logged in users should be downloaded.

Created as an assgnment for rtCamp, Pune
