<template>
    <div>
        <div class="row my-3">
            <div class=" mb-3 col-md-12 d-flex flex-grow justify-content-between">
                 <v-alert type="success" dismissible v-if="alert">
                I'm a success alert.
                </v-alert>
                <form @submit.prevent="buscador()">
                        <input type="text" placeholder="buscar" v-on:keyup="buscador()"  v-model="texts" class="form-control w-50">
                </form>
                <v-spacer></v-spacer>
                  
                 <div class="my-2">
                    <v-btn color="primary" @click="dialog = !dialog">Agregar
                          <i class="material-icons">
                            add_circle_outline
                            </i>
                            


                    </v-btn>
                </div>
                
                 </div>

                <v-progress-linear
                indeterminate
                color="primary"
                v-if="progress"
                 ></v-progress-linear>
            <div class="col-md-12">
               
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Bodega</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody v-for="(producto,index) in arrayFilter" :key="index">
                        <tr>
                        <th scope="row">{{producto.nombre}}</th>
                        <td>{{producto.bodegaNombre}}</td>
                        <td>{{producto.cantidad}}</td>
                        <td><h3 class="h6 text-center w-75 text-white p-1 rounded" v-bind:class="{'bg-success':producto.estado,'bg-danger':!producto.estado}">{{producto.estado?'activo':'inactivo'}}</h3></td>
                        
                        <td style=""><h3 @click="cambiarEstado(producto.id,index)" class="cambiar bg-dark text-center w-75 h6 p-1 rounded text-white" >Cambiar estado</h3></td>
                        
                        </tr>
                    
                    </tbody>
                </table>

            </div>
            <div class="col-md-6" >
              
               <v-btn color="primary" @click="llenarDatos(pagination.prev_page_url)"  :disabled="!pagination.prev_page_url">Prev</v-btn>
              {{pagination.current_page}}
                <v-btn color="primary" @click="llenarDatos(pagination.next_page_url)" class="text--red">next</v-btn>
            </div>

            <div class="col-md-6" v-if="formEstado">
               <div class="card">
                    <div class="card-header d-flex flex-row justify-content-between align-content-center">
                         <h3 class="m-0"> Agregar Producto</h3> 
                         <div @click="gestionForm()">
                                <i   class="material-icons p-0 ">
                                                        clear
                                                    </i>
                         </div>
                     
                    </div> 
                    <div class="card-body">
                          <form @submit.prevent="enviar">
                            <div class="form-group">
                                <input type="text" class="form-control" v-model="form.nombre" placeholder="Nombre de Producto">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control"   v-model="form.cantidad"  placeholder="Cantidad de Producto" >
                            </div>
                             <div class="form-group" >
                                 <select class="form-control" v-model="form.bodega" >
                                    <option disabled value="">Selccione Bodega</option>
                                    <option v-for="(bodega,index) in bodegas" :key="index" v-bind:value="bodega.id">{{bodega.nombre}}</option>
                                    
                                    </select>
        
                            </div>
                             <div class="form-group" >
                                 <select class="form-control" v-model="form.estado" >
                                     <option disabled value="">Seleccione estado</option>
                                    <option v-bind:value="true">Activo</option>
                                    <option v-bind:value="false">Inactivo</option>
                                    </select>
        
                            </div>
                        
                           
                        </form>
                    </div>
                </div>
                            
      
           
           
            </div>

          <!-- dialog!!! form!!-->
 <v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Crear Producto</span>
          <v-spacer></v-spacer>
         <i class="material-icons" @click="dialog= !dialog">
            clear
         </i>
        </v-card-title>
        <v-card-text>
          <v-container>
            
                <form @submit.prevent="enviar"  ref="form"
                >
                <v-row>
                    <v-col cols="12">
                        <v-text-field label="Nombre de producto" v-model="form.nombre" required></v-text-field>
                    </v-col>
            
              <v-col cols="12">
                <v-text-field label="Cantidad"  v-model="form.cantidad" required></v-text-field>

              </v-col>
              <v-col cols="12">
                <v-select required
                    :items="bodegas"
                     item-text="nombre"
                     item-value="id"
                    label="Bodegas"
                    v-model="form.bodega"
                    >
                </v-select>
              </v-col>
              <v-col cols="12" >
                  <v-select required
                  :items="estadoDisponibles"
                  item-text="nombre"
                  item-value="value"
                  label="Seleccione el Estado"
                  v-model="form.estado"
                  >                    
                  </v-select>
              </v-col>

             </v-row>
             </form>
           
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="enviar" color="blue darken-1" text>Save</v-btn>
          <v-btn color="blue darken-1" text @click="dialog= !dialog">Close</v-btn>
         
        </v-card-actions>
       
      </v-card>
    </v-dialog>
  </v-row>                 
         



        </div>

    </div>
</template>

<script>
export default {
    name:'prueba',
    data(){
        return{
            estado:true,
            formEstado:false,
            dialog:false,
            bodegas:'',
            progress:false,
            productos:'',
            alert:false,
            estadoDisponibles:[{
            nombre:'activo',value:true},
            {
            nombre:'inactivo',value:false,}
            ],
            pagination:{
            'total':'',
            'current_page':'',
           'nex_page_url':'',
           'prev_page_url':''
            },
           
            form:{
                nombre:'',
                cantidad:'',
                estado:'',
                bodega:''
            },
             texts:'',
            
        }
    },methods:{
        gestionForm(){
            if(this.formEstado){
                this.formEstado=false
            }else{
                 this.formEstado=true
            }        
        },
        prueba(){
            console.log(this.form);
            
            console.log('preu');
            
        }
        ,
        cambiarEstado(id,index){
           var confirms= confirm('SEGURO DESEA CAMBIAR EL ESTADO?');
           
            if(confirms){
              
                var datos={
                    id
                
                }
                axios.post('/productos/estado',datos).then(res=>{
                  this.productos.map(producto=>{
                      if (producto.id==id) {
                          producto.estado=res.data.estado
                      }
                  });
                  
                    
                })  
            }
        },makePagination(data){
                let pagination={
                    current_page:data.current_page,
                    last_page:data.last_page,
                    next_page_url:data.next_page_url,
                    prev_page_url:data.prev_page_url
                }
                this.pagination=pagination;
               
                
        },
        enviar(){
            var valores=this.form;
 
            
           axios.post('/productos',valores).then(res=>{
               
               this.productos.push(res.data);
               this.dialog=false;
              this.alert=true;
           }).catch(err=>{
               console.log(err)
           })
            
        },buscador(){
            this.texts=this.texts.toLowerCase();
          
            
        },llenarDatos(page){
            this.progress=true;
           let url;
           if (this.pagination.next_page_url=='' || this.pagination.next_page_url==null || this.pagination.next_page_url==undefined) {
               url='/productos';
           }else{
             url=page ;
           }
           

           axios.get('/bodega').then(res=>{
            this.bodegas=res.data; });
        
              axios.get(url).then(res=>{
                console.log(res.data.data,'res.data');
                
                this.productos=res.data.data;
                this.makePagination(res.data);          
                 this.progress=false;
            });
        }, 
        
        
     
    },
    async created(){
       
         this.llenarDatos(undefined);
        
    },computed:{
            arrayFilter:function(){
                    var arrayFilters=[];
                    for(let producto of this.productos){
                    let nombre =producto.nombre.toLowerCase();
                    if (nombre.indexOf(this.texts)>=0) {
                        arrayFilters.push(producto);
                    }
                }
                console.log(arrayFilters);
                
                return arrayFilters;
            },
            paginationIsActive(){
                return this.pagination.current_page;//pagina actual
            },
        },
           
    

}
</script>
<style scoped>
    .cambiar,.material-icons{
        cursor: pointer;
    }
</style>