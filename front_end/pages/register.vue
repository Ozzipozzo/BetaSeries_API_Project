<template>
  <div>
    <section class="form main__margin--top">
        <legend><strong>  Formulaire d'inscription </strong></legend><br>
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
        <label htmlFor="email" class="form__labels"
          >Saisissez votre email</label
        >
        <input
          type="email"
          name=""
          id="email"
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
        @click="SuscribeUser"
      >
        S'inscrire
      </button>
    </section>
  </div>
</template>


<script>
export default {
  name: "Register",
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
      email: null
    };
  },
  methods: {
    SuscribeUser() {
      (this.login = document.getElementById("login").value),
        (this.password = document.getElementById("password").value);
        (this.email = document.getElementById("email").value);
      fetch("http://localhost:8000/api/users/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          login: this.login,
          password: this.password,
          email: this.email,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            alert('Compte crée !')
        //   this.$router.push('/login')
        }).catch(function(err) {
            console.log(err);
            alert("Un problème est survenu !")
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