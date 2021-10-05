<template>
  <div class="main_nav">
    <div class="site-title">
      <svg viewBox="0 0 1350 200">
          <text x="30%" y="50%" fill="transparent" textAnchor="middle" className="sans-serif">
              Nuxt Flicks
          </text>
      </svg>
    </div>
    <nuxt-link to="/">Home</nuxt-link>
    <nuxt-link to="/register">Inscription</nuxt-link>
    <nuxt-link to="/login">Connexion</nuxt-link>
    <nuxt-link to="/myseries">Mes séries</nuxt-link>
    <nuxt-link to="/friends">Liste d'amis</nuxt-link>
    <nuxt-link to="/searchserie">Recherche</nuxt-link>
    <span @click="Disconnect" style="cursor: pointer; color: #F7D633;">Déconnexion</span>
  </div>
</template>

<script>
export default {
  data() {
      return {
          isConnected: '',
      };
  },
  mounted() {
      this.isConnected = localStorage.getItem("userToken");
  },
  methods: {
    Disconnect() {
      localStorage.setItem("userId", '');
      localStorage.setItem("userLogin", '');
      localStorage.setItem("userToken", '');
      localStorage.setItem("userSeries", '');
      this.$router.push('/login')
    }
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@300;400&display=swap');

@media (max-width: 900px) {
  div.main_nav {
    width: 100%;
    flex-wrap: wrap;
  }
  .main_nav > a, .main_nav > span {
   width: 100%;
   text-align: center;
   padding: 5px;
 }
}
 .main_nav {
  font-family: 'Manrope', sans-serif;
  display: flex;
  justify-content: space-evenly;
  width: 50%;
  margin: 0 auto;
  text-decoration: none;
  margin-top: 2em;
  padding-bottom: 2em;
  margin-bottom: 2em;
  border-bottom: 1px solid #F7D633;
  flex-wrap: wrap;
 }
 .main_nav > a {
   text-decoration: none;
   color: #F7D633;
 }

 .main_nav > a:hover, .main_nav > span:hover {
   text-decoration: underline;
   color: orange;
 }

.site-title {
  width: 100%;
}

.text-center {
  text-align: center;
}

svg text {
  stroke: #F7D633;
  font-size: 100px;
  font-weight: bold;
  stroke-width: 3;
  animation: textAnimate 3s infinite alternate;
}

@keyframes textAnimate {
  0% {
      stroke-dasharray: 0 50%;
  }
  100% {
      stroke-dasharray: 50% 0;
  }
}
</style>