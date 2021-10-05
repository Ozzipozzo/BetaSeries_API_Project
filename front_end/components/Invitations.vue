<template>
    <div>
        <!-- <p>{{ user.login }}</p>
        <button v-if="user.in_account == true" @click="BlockUser">Bloquer</button>
        <button v-if="isBlocked == false" @click="RemoveUser">Retirer</button>
        <button v-if="isBlocked == true" @click="UnblockUser">Débloquer</button> -->

        <p v-if="usersList.friends.users.includes(invitation.ref_id)"> {{invitation.text}} <button @click="RemoveUser">Retirer</button> <button @click="UnblockUser">Débloquer</button>  <button @click="RemoveInvitation"> Supprimer l'invitation</button> </p>
        <p v-else-if="usersList.blocked.users.includes(invitation.ref_id)"> {{invitation.text}} <button @click="UnblockUser">Débloquer</button>  <button @click="RemoveInvitation"> Supprimer l'invitation</button> </p>
        <p v-else> {{invitation.text}} <button @click="AddFriend">Ajouter en ami</button> <button @click="RemoveInvitation"> Supprimer l'invitation</button> </p>
    </div>
</template>

<script>

export default {
    name: 'Invitations',
    props: ['invitation', 'usersList'],
    data() {
        return {
            friend: null
        };
    },
    mounted() {
        
    },

    methods: {
        BlockUser() {
            fetch('http://127.0.0.1:8000/api/users/block/friends', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: this.invitation.ref_id,
                    token: localStorage.getItem('userToken')
                })
            })
            .then((res) => res.json())
            .then((data) => {
                console.log(data);
                alert("L'utilisateur a été bloqué !")
            }).catch(function(err) {
                alert("Une erreur est survenu !")
            });
        },
        RemoveUser() {
            fetch('http://127.0.0.1:8000/api/users/remove/friends', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: this.invitation.ref_id,
                    token: localStorage.getItem('userToken')
                })
            })
            .then((res) => res.json())
            .then((data) => {
                alert("L'utilisateur a été supprimé de vos amis !")
            }).catch(function(err) {
                alert("Une erreur est survenu !")
            });
        },
        UnblockUser() {
            fetch('http://127.0.0.1:8000/api/users/unblock/friends', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: this.invitation.ref_id,
                    token: localStorage.getItem('userToken')
                })
            })
            .then((res) => res.json())
            .then((data) => {
                alert("L'utilisateur a été débloqué !")
            }).catch(function(err) {
                alert("Une erreur est survenu !")
            });
        },
        AddFriend() {
            fetch('http://127.0.0.1:8000/api/users/add/friends', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    token: localStorage.getItem('userToken'),
                    id: this.invitation.ref_id
                })
            })
            .then((res) => res.json())
            .then((data) => {
                alert("L'utilisateur a été ajouté !")
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !")
            });
        },
        RemoveInvitation() {
            fetch('http://127.0.0.1:8000/api/users/delete/notification', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    token: localStorage.getItem('userToken'),
                    id: this.invitation.id
                })
            })
            .then((res) => res.json())
            .then((data) => {
                console.log(data);
                alert("Invitation supprimée !")
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !")
            });
        }
    },
};
</script>

<style>

</style>