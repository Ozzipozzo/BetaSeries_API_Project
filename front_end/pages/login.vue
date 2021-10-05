<template>
  <div>
    <section class="form main__margin--top">
        <legend><strong> Formulaire de connexion </strong> </legend><br>

      <div class="form-group">
        <label htmlFor="login" class="form__labels"
          >Saisissez votre login</label
        >
        <input
          type="text"
          name=""
          id="login"
          required
        />
      </div>

      <div class="form-group">
        <label htmlFor="password" class="form__labels"
          >Écrivez votre mot de passe</label
        >
        <input
          type="password"
          name="password"
          id="password"
          required
        />
      </div>

      <button
        id="inscription_submit"
        class="btn btn-primary"
        @click="ConnectUser"
      >
        Se connecter
      </button>
    </section>
  </div>
</template>


<script>
export default {
  name: "Login",
  data() {
    return {
      isLoading: true,
      content: null,
      id: null,
      pseudo: null,
      login: null,
      avatar: null,
      presentation: null,
      password: null,
    };
  },
  methods: {
    ConnectUser() {
      (this.email = document.getElementById("login").value),
        (this.password = document.getElementById("password").value);
      fetch("http://localhost:8000/api/users/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          login: this.email,
          password: this.password,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          localStorage.setItem("userId", data.member.id);
          localStorage.setItem("userLogin", data.member.login);
          localStorage.setItem("userToken", data.member.token);
          let showArray = [];
          data.member.showList.forEach(element => {
            showArray.push(JSON.stringify(element));
          });
          
          localStorage.setItem("userSeries", showArray);
          this.$router.push('/')
        }).catch(function() {
            alert("Mauvais identifiant ou mot de passe !")
        });
    },
  },
};
</script>

<style>
.form {
  box-sizing: border-box;
  width: 320px;
  padding: 18px;
  margin: auto;
  background-color: rgb(29, 27, 27);
  border-radius: 15px;
  box-shadow: 8px 8px 10px rgba(0, 0, 0, 0.4);
  color: white;
}
.form-group input {
  margin: 15px 0px;
  width: 100%;
  box-sizing: border-box;
}
.form button {
  margin: 15px 0px;
  width: 100%;
  font-size: 18px;
}
.form a img {
  margin: 15px 0px;
  width: 100%;
}
</style>