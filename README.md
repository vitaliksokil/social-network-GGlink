Site: http://f0443615.xsph.ru

Super admin data:
* E-mail : admin@admin.com
* Password: apTRX8HGec
    
---

# Social network "GGlink"
The site is a social network for gamers, where every fan of games can find friends, with whom he or she can spend time playing video games.
GGlink allow you to create your own communities, to communicate with people,to look for people for your team.

---

### Short description of the project

#### Registration
I've used the **authentication scaffolding** provided by the **make:auth**.
User should login and **confirm his e-mail** to work with the site. User can **reset his password** if he forget it , as well.

---

#### Profile
Once user has been logged in he will see his profile page. He will see his photo, his bio, his favourite games, some of his friends and communities. There is also profile wall, where every user can left his comment about the user. Owner of the profile can delete any comment, and owner of the comment can delete his comment. In the header there is dropdown list where user can go to **Edit**, **Settings** pages and **Logout** from his account. On **Edit** page user can change any info about him, even password, email and of course photo. On **Settings** page user can set settings that are responsible for who can write messages to user or who can left comments on his profile page(nobody,only friends, all users)

P.S. To work with user permissions(authenticate user actions) i used **laravel policies and gates**

#### Friends
On **My friends** page user can see list of all of his friends, online friends, new friends(people who wanna be friends), and requested(offers that you sent to another users to be friends). Request to be friends you can send being on the other user's page, there is a button. 

#### Messages
On **Messages** page user can see list of his conversations. This is Vue component, which works in real time(if the user recevies a new message, he will see it immediately). He will be also informed about new message by pop up window in the left down corner. 
User can choose conversation and start chatting with another person. This component is also real time. User will see immediately if another user is typing message, all unread messages, new messages. User will see only 100 last messages, but by scrolling up it will be uploading 100 more.

##### Work of messanger(click to watch)
[![Work of messanger](http://pixs.ru/images/2020/06/22/messages0.jpg)](https://www.youtube.com/watch?v=qUen7y_1-Us "Work of messanger")
##### Message notification(click to watch)
[![Work of message notification](http://pixs.ru/images/2020/06/22/messages0c0e1454ad971f2ef.jpg)](https://www.youtube.com/watch?v=yLy4_vZsP5U "Work of message notification")

P.S Working with chat for real time i used **Puhser,Vue.js,Laravel notifications,events,broadcasting**

#### Games / All games
There are 2 pages **Games** and **All games**. On **Games** page there is list of games that user is subscribed to. On **All games** page there are all games, it can be understandable from the name. **Game** - it's something like community, you can subscribe any game to read some news about the game. **Game** can be created by super admin, super admin can give roles to another subscribers(moderator - can delete,add posts). Super admin can edit,delete,add games.
#### Communities / All communities
It is analogically to games except community can be created by any user and creator of the community can set roles of subscribers(moderator-can delete,add posts, admin - the same as moderator + can edit community info and can also set another users as moderators)

#### Rooms
**Rooms** - is a place where any user can create his own room and other users can join it, they can assemble a team there, chat with each other, and then maybe they can play some games together.
Room is connected to a game. It is working in real time. Creator of a room can kick other players, can lock the room that nobody else can join, can delete room.

##### Work of rooms(click to watch)
[![Work of rooms](http://pixs.ru/images/2020/06/22/rooms0.jpg)](https://www.youtube.com/watch?v=Hrm0mKGJUEA "Work of rooms")

#### All users
It's just a list of all users, nothing interesting :)

## Topics i learned or improve :)
* Laravel topics:
    * controllers
    * models(relations)
    * view(blade)
    * policies
    * gates 
    * notifications
    * events
    * broadcasting
* Vue.js
* Pusher

maybe something else :)
