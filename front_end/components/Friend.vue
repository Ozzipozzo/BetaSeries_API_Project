<template>
    <div>
        <p>{{ user.login }}</p>
        <button v-if="user.in_account == true" @click="BlockUser">Bloquer</button>
        <button v-if="isBlocked == false" @click="RemoveUser">Retirer</button>
        <button v-if="isBlocked == true" @click="UnblockUser">Débloquer</button>
    </div>
</template>

<script>

export default {
    name: 'Friend',
    props: ['user', 'isBlocked'],
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
                    id: this.user.id,
                    token: localStorage.getItem('userToken')
                })
            })
            .then((res) => res.json())
            .then((data) => {
                console.log(data);
                alert(this.user.login + " a été bloqué !")
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
                    id: this.user.id,
                    token: localStorage.getItem('userToken')
                })
            })
            .then((res) => res.json())
            .then((data) => {
                alert(this.user.login + " a été supprimé de vos amis !")
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
                    id: this.user.id,
                    token: localStorage.getItem('userToken')
                })
            })
            .then((res) => res.json())
            .then((data) => {
                alert(this.user.login + " a été débloqué !")
            }).catch(function(err) {
                alert("Une erreur est survenu !")
            });
        }
    },
};
</script>

<style>

</style>