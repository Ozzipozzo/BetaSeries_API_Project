<template>
    <div>
        <div v-if="usersList == null">
            Loading
        </div>
        <div v-else >
            <div>
                <div class="form">
                    <div class="form-group">
                        <p>Bonjour {{ currentUser }} </p>
                        <label for="search">Écrivez l'ami à inviter</label>
                        <input id="search" name="search" type="text" required />
                        <button @click="SearchUsers">Rechercher</button>
                    </div>
                </div>
                <div v-if="searchList == null">
                </div>
                <div v-else class="friends__33">
                    <p v-for="user in searchList.users" :key="user.id"> {{user.login}} <br /> <button @click="AddFriend(user.id)">Ajouter en ami</button> </p>
                </div>
            </div>
            <div class="friends__container">
                <h2>Mes amis</h2>
                <Friend 
                    v-for="user in usersList.friends.users" 
                    :key="user.id"
                    :user="user"
                    :isBlocked="false"
                />
                <h2>Personnes bloquées</h2>
                <Friend 
                    v-for="user in usersList.blocked.users" 
                    :key="user.id"
                    :user="user"
                    :isBlocked="true"
                />
                <h2>Invitations en amis</h2>
                <Invitations 
                    v-for="item in usersList.invitations.notifications"
                    :key="item.ref_id"
                    :invitation="item" 
                    :usersList="usersList"

                />
                <!-- <div v-for="invitation in usersList.invitations.notifications" :key="invitation.id">
                    <div v-for="invitation in usersList.invitations.notifications" :key="invitation.id">
                    </div>
                    <p v-if="usersList.friends.users.includes(invitation.ref_id)"> {{invitation.text}} </p>
                    <p v-else> {{invitation.text}} </p>
                </div> -->
            </div>
        </div>
    </div>
</template>

<script>
import Friend from "../components/Friend.vue"
import Invitations from "../components/Invitations.vue";

export default {
    components: {
        Friend,
        Invitations
    },
    data() {
        return {
            usersList: null,
            searchList: null,
            currentUser: null
        };
    },
    mounted() {
        this.currentUser = localStorage.getItem('userLogin');
        fetch("http://localhost:8000/api/users/friends", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                id: localStorage.getItem("userId"),
                token: localStorage.getItem("userToken"),
            }),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            this.usersList = data;
        }).catch(function(err) {
            alert("Une erreur est survenu !")
        });
    },

    methods: {
        AddFriend(userId) {
            fetch('http://127.0.0.1:8000/api/users/add/friends', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    token: localStorage.getItem('userToken'),
                    // login: document.getElementById('search').value,
                    id: userId
                })
            })
            .then((res) => res.json())
            .then((data) => {
                if(data == "not found") {
                    alert("L'utilisateur " + document.getElementById('search').value + " n'existe pas !")
                } else {
                    alert("L'utilisateur " + document.getElementById('search').value + " a été ajouté !")
                }
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !")
            });

            // Rafraichit les amis
            fetch("http://localhost:8000/api/users/friends", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: localStorage.getItem("userId"),
                    token: localStorage.getItem("userToken"),
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                this.usersList = data;
            }).catch(function(err) {
                alert("Une erreur est survenu !")
            });
        },
        SearchUsers() {
            let toSearch = document.getElementById('search').value;
            if(toSearch.length >= 2) {
                fetch('http://127.0.0.1:8000/api/users/find', {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        token: localStorage.getItem('userToken'),
                        login: toSearch
                    })
                })
                .then((res) => res.json())
                .then((data) => {
                    this.searchList = data;
                }).catch(function(err) {
                    console.log(err);
                    alert("Une erreur est survenu !")
                });
            }
        }
    },
};
</script>

<style>

.friends__33 {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    width: 80%;
    margin-left: auto;
}
.friends__33 p {
    width: 33%;
}
.friends__container{
    margin: auto;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    text-align: center;
    font-family:'Courier New', Courier, monospace;
}
.friends__container h2, .friends__container div {
    width: 100%;
}
</style>