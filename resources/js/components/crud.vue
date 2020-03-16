<template>
<div>
<v-container>
    <v-row align="center" class="">
        <v-col cols="4">
            <v-text-field v-model="text" label="Buscar"></v-text-field>
         </v-col>   
             <v-spacer></v-spacer>
            <v-btn @click="dialogForm = !dialogForm" class="mx-3" color="primary" >Crear Nota</v-btn>
       
    </v-row>
    <v-progress-linear
      indeterminate
      color="red"
      v-if="progreso"
    ></v-progress-linear>
      <v-row v-if="text.length <=0">
        <v-col cols="12">
           <v-simple-table>
               <template v-slot:default>
                    <thead>
                        <tr>
                            <td>Nombre</td>
                            <td>Cantidad</td>
                            <td>Bodega</td>
                            <td>Estado</td>
                            <td>Acciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(producto,index) of productosPagination " :key="index"> 
                            <td>{{producto.nombre}}</td>
                            <td>{{producto.cantidad}}</td>
                            <td>{{producto.bodegaNombre }}</td>
                            <td class="d-flex align-center">
                                <p  style="width: 70%;" class="white--text p-2 m-0 text-center estado" v-bind:class="{'green':producto.estado, 'red':!producto.estado}">{{producto.estado ==true ?'Activo' : 'Desactivado'  }} </p></td>
                            <td ><p @click="activeDialogConfirm(producto.id)" style="width: 70%;" class="indigo p-2  text-center white--text estado m-0">Cambiar Estado </p> </td>
                        </tr>
                    </tbody>

               </template>
           </v-simple-table>
        </v-col>
        <v-col colsp="6">
            <v-btn primary :disabled="!pagination.prev_page_url" @click="llenarDatos(pagination.prev_page_url)" >Prev</v-btn>
            {{pagination.current_page}}
            <v-btn primary :disabled="!pagination.next_page_url" @click="llenarDatos(pagination.next_page_url)" >Next</v-btn>
             
        </v-col>
    </v-row>

    <!--buscador tabla-->

      <v-row v-if="text.length >0">
        <v-col cols="12">
           <v-simple-table>
               <template v-slot:default>
                    <thead>
                        <tr>
                            <td>Nombre</td>
                            <td>Cantidad</td>
                            <td>Bodega</td>
                            <td>Estado</td>
                            <td>Acciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(producto,index) of filter " :key="index"> 
                            <td>{{producto.nombre}}</td>
                            <td>{{producto.cantidad}}</td>
                            <td>{{producto.bodegaNombre }}</td>
                            <td class="d-flex align-center">
                                <p  style="width: 70%;" class="white--text p-2 m-0 text-center estado" v-bind:class="{'green':producto.estado, 'red':!producto.estado}">{{producto.estado ==true ?'Activo' : 'Desactivado'  }} </p></td>
                            <td ><p @click="activeDialogConfirm(producto.id)" style="width: 70%;" class="indigo p-2  text-center white--text estado m-0">Cambiar Estado </p> </td>
                        </tr>
                    </tbody>

               </template>
           </v-simple-table>
        </v-col>
       
    </v-row>


    <!--Modal Form-->
    <v-row>
        <template>
  <v-row justify="center">
    <v-dialog v-model="dialogForm" persistent max-width="600px">
    
      <v-card>
        <v-card-title>
          <span class="headline">Nuevo Producto</span>
        </v-card-title>
        <v-card-text>
          <v-container>
             <form @submit.prevent="enviar()"> 
            <v-row>
              <v-col cols="12" >
                <v-text-field  v-model="formCreate.nombre" label="Nombre*" required></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Cantidad"  v-model="formCreate.cantidad" type="number" hint="Cuantos productos hay de este tipo"></v-text-field>
              </v-col>
             
            
              <v-col  sm="6">
                <v-select
                  :items="selectStatus"
                  item-text="nombre"
                  v-model="formCreate.estado"
                  item-value="valor"
                  label="Seleccione Estatus"
                  required
                ></v-select>
              </v-col>
                <v-col  sm="6">
                <v-select
                  :items="bodegas"
                   v-model="formCreate.bodega"
                  item-text="nombre"
                  item-value="id"
                  label="Seleccione Estatus"
                  required
                ></v-select>
              </v-col>
              
            </v-row>
            <v-spacer></v-spacer>
                <v-btn color="green white--text" type="submit">Enviar</v-btn>
                <v-btn color="red white--text" @click="dialogForm = !dialogForm">Cancelar</v-btn>
            </form>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
       
      </v-card>
    </v-dialog>
  </v-row>
</template>
    </v-row>
    <!--Modal Confirm-->
   <template>
        <v-row justify="center">
            <v-dialog v-model="dialogConfirm" persistent max-width="400 ">
            
            <v-card>
                <v-card-title class="headline">Seguro de Cambiar el estado?</v-card-title>
                <v-card-text>Haga Click en cancelar ó aceptar </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="red darken-1" text @click="declineDialogConfrim()">Cancelar</v-btn>
                <v-btn color="green darken-1" text @click="aceptDialogConfirm()">Aceptar</v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-row>
</template>
{{text}}
</v-container>
</div>
</template>

<script>
export default {
data(){
  return{
      productosPagination:'',
      productosGeneral:'',
      dialogConfirm:false,
      dialogForm:false,
      changeStatusId:'',
      text:'',
      bodegas:'',
      progreso:false,
      formCreate:{
          nombre:'',
          estado:'',
          bodega:'',
          cantidad:''
      },
      selectStatus:[{nombre:'Activo',valor:true},
       {nombre:'inactivo',valor:false}   
      ],
      pagination:{
          current_page:'',
          next_page_url:'',
          prev_page_url:''
      }
  }
},
methods:{
    llenarDatos(uri){
        var url;
           this.progreso=true;
        if(uri==null || uri == undefined || uri ==''){
            url='/productos';
        }else{
            url=uri;
        }
        axios.get(url).then(res=>{
         
            this.productosPagination=res.data.data;
            this.makePagination(res.data);
                 this.progreso=false;
        });
    },  llenarGeneral(){
        
     
        axios.get('/productos/general').then(res=>{
         
            this.productosGeneral=res.data;
           
            
        });
    },
    enviar(){
        console.log(this.formCreate);
        axios.post('/productos',this.formCreate).then(res=>{
            console.log(res.data);
              this.productosPagination.push(res.data);
              this.productosGeneral.push(res.data);
              this.dialogForm=false;
        }).catch(err=>{
            console.log(err);
          
        });
        
    },
    makePagination(data){
        this.pagination.prev_page_url=data.prev_page_url;
        this.pagination.current_page=data.current_page;
        this.pagination.next_page_url=data.next_page_url;
        console.log(this.pagination);
        
    },
    activeDialogConfirm(id){
        this.dialogConfirm=true
        this.changeStatusId=id;
    },
    aceptDialogConfirm(){
        console.log('acepto dialogo',this.changeStatusId);
        var data ={id:this.changeStatusId}
       
        axios.post('/productos/estado',data).then(res=>{
          console.log(res.data);
            this.productosPagination.map(producto=>{
                if (producto.id == res.data.id) {
                    producto.estado=res.data.estado;
                }
            })
              this.productosGeneral.map(producto=>{
                if (producto.id == res.data.id) {
                    producto.estado=res.data.estado;
                }
            })
            
            
        }).catch(err=>{
            console.log('error',err);
            
        })
         this.dialogConfirm=false
        this.changeStatusId='';
    },
    declineDialogConfrim(){
        this.dialogConfirm=false
        this.changeStatusId='';
    }

},
created(){
    this.llenarDatos(null);
    this.llenarGeneral();
    axios.get('bodega').then(res=>{
        this.bodegas=res.data;
    }).catch(err=>{
        console.log(err);
        
    })
},
computed:{
    filter(){
        var text=this.text.toLowerCase();
        var filters=[];
        for(let producto of this.productosGeneral){     
            let nombre=producto.nombre.toLowerCase();
            if (nombre.indexOf(text)>=0) {
                filters.push(producto);
            }
        }
        return filters;
    }
}

}

</script>

<style scoped>
    .estado{
        cursor: pointer;
       
        border-radius: 3px;
        
    }
</style>