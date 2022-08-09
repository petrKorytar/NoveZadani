<template>
   <div>
      <div id="myApp" class="m-6">
        <h1 class="text-2xl text-center font-bold">Redakční systém pro správu astronautů</h1>

        <!----------------------- Searching by ID,name,last name, birthdate or superpower. --------------------->
        <div class="m-6">
            <h2 class="text-xl font-bold">Vyhledávání:</h2>
            <table class="flex justify-center w-screen">
                <tr>
                    <td>
                        <input v-model="searchId" @keyup="filteredList('id')" 
                         type="text" class="border-solid border-2 rounded ml-2 px-1 py-0.5" placeholder="Id"/>
                    </td>
                    <td>
                        <input v-model="searchName" @keyup="filteredList('name')" 
                        type="text" class="border-solid border-2 rounded ml-2 px-1 py-0.5" placeholder="Jméno"/>
                    </td>
                    <td>
                        <input v-model="searchLastName" @keyup="filteredList('lastName')" 
                        type="text" class="border-solid border-2 rounded ml-2 px-1 py-0.5" placeholder="Příjmení"/>
                    </td>
                    <td>
                        <input v-model="searchBirthdate" @keyup="filteredList('birthdate')" 
                        type="text" class="border-solid border-2 rounded ml-2 px-1 py-0.5" placeholder="Narozeniny"/>
                    </td>
                    <td>
                        <input v-model="searchSuperpower" @keyup="filteredList('superpower')" 
                        type="text" class="border-solid border-2 rounded ml-2 px-1 py-0.5" placeholder="Superschopnost"/>
                    </td> 
                </tr>
            </table>
        </div>

        <div class="m-6">
        <!-------------------------- Add new user button--------------------------  -->
              <div class="flex justify-end">
                  <input @click="openModel"  value="Přidat" type="button" class="bg-green-200 hover:bg-green-400 p-2 rounded font-medium" >
              </div>

        <!--------------------- The table with description------------------------  -->
              <div class="flex justify-center">
                  <table class="text-center">
                      <h3 class="text-xl font-medium mb-2">Uživatelé</h3>
                        <tr class="bg-gray-400">
                            <th class="px-3 py-2 border border-black">ID</th>
                            <th class="px-3 py-2 border border-black">Jméno</th>
                            <th class="px-3 py-2 border border-black">Příjmení</th>
                            <th class="px-3 py-2 border border-black">Narozeniny</th>
                            <th class="px-3 py-2 border border-black">Superschopnost</th>
                        </tr>

        <!-- ----------------View of users------------------------  -->
                        <tr v-for="(row,index) in searchedArray" :key="index">
                            <td class="px-3 py-2 border border-black">{{row.id}}</td>
                            <td class="px-3 py-2 border border-black">{{row.firstName}}</td>
                            <td class="px-3 py-2 border border-black">{{row.lastName}}</td>
                            <td class="px-3 py-2 border border-black">{{row.birthdate}}</td>
                            <td class="px-3 py-2 border border-black">{{row.superpower}}</td>
                            <td>
                                <button @click="fetchData(row.id)" type="button" name="edit"  class="bg-yellow-100 hover:bg-yellow-200 p-1 rounded font-medium">
                                    Upravit
                                </button>
                            </td>
                            <td>
                                <button @click="deleteData(row.id)" type="button" name="delete"  class="bg-red-200 hover:bg-red-300 p-1 rounded font-medium">
                                    Smazat
                                </button>
                            </td>
                        </tr>
                  </table>
            </div>
        </div>

        <!--------------- The table for add new user. -------------------- -->
        <div v-if="myModel">
            <div class=" absolute w-full h-full bg-gray-800/50 top-0 left-0 z-10">
                <div class="bg-white rounded-lg absolute top-1/4 inset-x-1/4 z-20 ">
                    <div class="flex  justify-between p-5">
                      <h4 class="text-xl font-semibold">{{dynamicTitle}}</h4>
                      <button @click="myModel=false" type="button" class="text-2xl rounded hover:bg-gray-500">
                          <span aria-hidden="true" class="p-1">&times;</span>
                      </button>        
                    </div>
                    <div class="p-10 grid justify-items-center">
                        <div class="">
                            <input v-model="firstName" type="text" placeholder="Jméno" class="border-solid border-2 rounded ml-2 px-1 py-0.5">
                        </div>
                        <div class="mt-2">
                            <input v-model="lastName" type="text" placeholder="Příjmení" class="border-solid border-2 rounded ml-2 px-1 py-0.5">
                        </div>
                        <div class="mt-2">
                            <input v-model="birthdate" type="text" placeholder="Narozeniny" class="border-solid border-2 rounded ml-2 px-1 py-0.5">
                        </div>
                        <div class="mt-2">
                            <input v-model="superpower" type="text" placeholder="Superschopnost" class="border-solid border-2 rounded ml-2 px-1 py-0.5">
                        </div>
                        <div align="center" class="mt-5">
                            <input v-model="hiddenId" type="hidden" >
                            <input type="button" class="bg-green-400 hover:bg-green-400/50 p-2 rounded font-medium" v-model="actionButton" @click="submitData">
                        </div>           
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
  import axios from "axios"

 export default {
  data(){
    return{
      allData:[],
      myModel:false,
      actionButton:"Vložit",
      dynamicTitle:"Přidat astronauta",
      searchName:"",
      searchLastName:"",
      searchBirthdate:"",
      searchSuperpower:"",
      searchId:"",
      searchedArray:[],
    }
  },
  methods: {
     async fetchAllData(){
       await  axios.post('https://nove-zadani.vercel.app/NoveZadani/public/API/index.php',{action:'fetchall'})
            .then((response)=>{
                this.allData = response.data
                this.searchedArray= response.data
            })
            .catch((error)=>{
                console.log(error)
            })
    },
      // Filters by id or first name or last name or birthdate or superpower.
      filteredList(search){
        
          switch(search){
            case 'name':
              this.searchedArray = this.allData.filter(
                (searched)=>searched.firstName.toLowerCase().includes(this.searchName.toLowerCase()))
                break;
            case 'lastName':
              this.searchedArray = this.allData.filter(
                (searched)=>searched.lastName.toLowerCase().includes(this.searchLastName.toLowerCase()))
                break;
            case 'birthdate':   
              this.searchedArray = this.allData.filter(
                (searched)=>String(searched.birthdate).includes(this.searchBirthdate))
                break;
            case 'id':
              this.searchedArray = this.allData.filter(
                (searched)=>String(searched.id).includes(this.searchId))
                break;
            case 'superpower':
              this.searchedArray = this.allData.filter(
                (searched)=>searched.superpower.toLowerCase().includes(this.searchSuperpower.toLowerCase()))
                break;
          }
      },

      // Open table for add or edit user.
      openModel(){
          this.firstName=""
          this.lastName=""
          this.birthdate=""
          this.superpower=""
          this.actionButton = "Vložit"
          this.dynamicTitle = "Přidat uživatele"
          this.myModel = true
    },
   async submitData(){
        if(this.firstName !=""&& this.lastName!="" && this.birthdate!=""&& this.superpower!=""){

            // Insert new user.
                if(this.actionButton=="Vložit"){

                  await  axios.post('https://nove-zadani.vercel.app/NoveZadani/public/API/index.php',{
                        action:'insert',
                        firstName:this.firstName,
                        lastName:this.lastName,
                        birthdate:this.birthdate,
                        superpower:this.superpower,

                    }).then((response)=>{
                        this.myModel = false
                        this.fetchAllData()
                        this.firstName=""
                        this.lastName=""
                        this.birthdate=""
                        this.superpower=""
                    })
                }

            // Edit exist user.
                if(this.actionButton == "Upravit"){
                  await axios.post('https://nove-zadani.vercel.app/NoveZadani/public/API/index.php',{
                        action:'update',
                        firstName: this.firstName,
                        lastName: this.lastName,
                        birthdate:this.birthdate,
                        superpower:this.superpower,
                        hiddenId: this.hiddenId

                    }).then((response)=>{
                        this.myModel = false
                        this.fetchAllData()
                        this.firstName=""
                        this.lastName=""
                        this.birthdate=""
                        this.superpower=""
                        this.hiddenId = ""
                    })
                }
        }
        else{
            alert("Něco jsi nezadal!")
        }
    },

     //It captures the data of a single user.
    fetchData(id){
        axios.post('https://nove-zadani.vercel.app/NoveZadani/public/API/index.php',{
            action:'fetchSingle',
            id:id

        }).then((response)=>{
            this.firstName = response.data.firstName
            this.lastName = response.data.lastName
            this.birthdate = response.data.birthdate
            this.superpower = response.data.superpower
            this.hiddenId = response.data.id
            this.myModel = true
            this.actionButton = 'Upravit'
            this.dynamicTitle = 'Upravit data'
        })
    },
    
    // Delete user by id.
    deleteData(id){
        if(confirm("Opravdu chceš tento záznam smazat?")){
            axios.post('https://nove-zadani.vercel.app/NoveZadani/public/API/index.php',{
                action:'delete',
                id:id

            }).then((response)=>{
                this.fetchAllData()
            })
        }
    },
  },

  created() {
    this.fetchAllData(); 
    }
 }
</script>

