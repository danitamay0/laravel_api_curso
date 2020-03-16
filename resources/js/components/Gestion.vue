<template>
    <v-container>
          
            <v-row>
                <v-col cols="6">
                    <v-text-field label="Buscar"></v-text-field>
                </v-col>
                <v-spacer></v-spacer>
                <v-btn @click="crear = !crear" color="success">Crear
                </v-btn>
            </v-row>
        <v-row>
        <v-col cols="8">
        <v-simple-table dark> 

        <table>
            <thead >
                <tr>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Bodega</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(producto,index) of productosPaginados" :key="index">
                    <td>{{producto.nombreProducto}}</td>
                    <td>{{producto.cantidad}}</td>
                    <td>{{producto.nombreBodega}}</td>
                    <td><v-btn class="red" @click="gestionar(producto.producto_id ,producto.nombreProducto,producto.bodega_id,producto.nombreBodega,producto.cantidad)" >Gestionar</v-btn></td>
                </tr>
            </tbody>
        </table>
        </v-simple-table>
        </v-col>
        <v-col cols="4">
            {{bodegas}}
            <br>
            {{form}}
            <hr>
            {{productoGestion}}
        </v-col>
        <v-row>
            <v-col cols="6">
                 <v-btn-toggle
                    rounded
                 >
                    <v-btn @click="currentPage--" v-if="currentPage!=1">
                     <v-icon>mdi-step-backward</v-icon>
                    </v-btn>
                    <v-btn v-for="pageNumber of pages.slice(currentPage-1,currentPage+3)" :key="pageNumber" 
                        @click="currentPage=pageNumber">
                       {{pageNumber}}
                    </v-btn>
                    <v-btn @click="currentPage++" v-if="currentPage<pages.length">
                        <v-icon>mdi-step-forward</v-icon>
                    </v-btn>
                </v-btn-toggle>
            </v-col>
        </v-row>
        </v-row>

                
        <v-row justify="center">
            <v-dialog v-model="crear" persistent max-width="600px">
        
            <v-card>
                <v-card-title>
                <span class="headline">Crear Producto</span>
                </v-card-title>
                <v-card-text>
                <v-container>
                    <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field label="Nombre de producto*" v-model="form.nombre" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field label="Cantidad" v-model="form.cantidad" hint="cantidad en bodega"></v-text-field>
                    </v-col>
                    
                  
                    <v-col cols="12">
                    <h4 v-if="error" class="red--text">{{error}}</h4>    
                     </v-col>   
                    </v-row>
                      <v-col cols="12" sm="6">
                        <v-select
                        :items="bodegas"
                        v-model="form.bodega_id"
                        
                        item-text="nombre"
                        item-value="id"
                        label="Selecciona Bodega"
                        required
                        ></v-select>
                    </v-col>
                </v-container>

                
                <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="crear = false; error=''; form.nombre='';form.cantidad=''; form.bodega_id='' ">Close</v-btn>
                <v-btn color="blue darken-1" text @click="guardar()">Save</v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-row>

        <v-row justify="center">
            <v-dialog v-model="gestionDialogo" persistent max-width="600px">
        
            <v-card>
                <v-card-title>
                <span class="headline">Gestionar Bodega</span>
                </v-card-title>
                <v-card-text>
                <v-container>
                    <v-row>
                    <v-col cols="12" sm="6" md="6">
                       <h3>{{productoGestion.nombre}}</h3>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                       <h3>{{productoGestion.nombreOrigen}}</h3>
                    </v-col>
                    
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field label="Cantidad" :max="productoGestion.cantidadOrigen" v-model="productoGestion.cantidad"  hint="cantidad a la bodega"></v-text-field>
                    </v-col>
                       <v-col cols="12" sm="6" md="6">
                       <h5>Cantidad Disponible:</h5>
                       <h5 class="red--text"> {{productoGestion.cantidadOrigen}}</h5>
                    </v-col>
                  
                    <v-col cols="12">
                   
                     </v-col>   
                    </v-row>
                      <v-col cols="12" sm="6">
                        <v-select
                        :items="productoGestion.bodegas"
                        v-model="productoGestion.destino_id"
                        
                        item-text="nombre"
                        item-value="id"
                        label="Selecciona Bodega"
                        required
                        ></v-select>
                    </v-col>
                     <h4 v-if="error" class="red--text">{{error}}</h4>    
                </v-container>

                
                <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="gestionDialogo = false; error=''; form.nombre='';form.cantidad=''; form.bodega_id='' ">Close</v-btn>
                <v-btn color="blue darken-1" text @click="guardarGestion()">Save</v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-row>

    </v-container>

</template>

<script>
export default {
    data(){
        return{
            productos:'',
            bodegas:'',
            form:{
                bodega_id:'',
                nombre:'',
                cantidad:''
            },
            productoGestion:{
                nombre:'',
                id:'',
                nombreOrigen:'',
                origen_id:'',
                destino_id:'',
                cantidad:'',
                cantidadOrigen:'',
                bodegas:[]
            },
            error:'',
            crear:false,
            gestionDialogo:false,
            currentPage:1,
            perPage:5,
            pages:[]

        }

    },
    methods:{
        guardar(){
           if (!this.form.bodega_id) {
               this.error='Seleccione una bodega'
               return false;
           }

            axios.post(`/producto/${this.form.bodega_id}`,this.form).then(res=>{console.log(res,'ress');
                 this.productos.push(res.data)
            
                 this.crear=false})
            .catch(err=>{
                console.log(err.response.data.err,err.response.data,);
                 
                 if(err.response.data.err){
                     this.error=err.response.data.err
                 }
                 if (err.response.data.error.cantidad) {
                     this.error='la cantidad es requerida!!'
                 }
                  if (err.response.data.error.nombre) {
                     this.error='el nombre es requerido!!'
                 }
                }
           );

        },
        gestionar(producto_id, nombreProducto,origen_id,nombreOrigen,cantidadOrigen){
        
            this.productoGestion.bodegas=[];
            this.productoGestion.nombreOrigen=nombreOrigen;
            this.productoGestion.origen_id=origen_id;
            this.productoGestion.nombre=nombreProducto;
            this.productoGestion.id=producto_id;
            this.productoGestion.cantidadOrigen=cantidadOrigen;
            
            this.productos.map(producto=>{
                if (producto.producto_id==this.productoGestion.id) {
                     this.productoGestion.bodegas.push({nombre:producto.nombreBodega,id:producto.bodega_id})
                }
            });

           this.productoGestion.bodegas= this.productoGestion.bodegas.filter(el=>{
                    return el.id!=origen_id
            });

            this.gestionDialogo=true;
        },
       async guardarGestion(){
            if (this.productoGestion.cantidad > this.productoGestion.cantidadOrigen) {
                    this.error='cantidad insuficiente'
                    return false;
            }

           var data={
                producto_id:this.productoGestion.id,
                cantidadADestino:this.productoGestion.cantidad
            }

            try {
        
               var respuesta=await axios.post(`/producto/${this.productoGestion.origen_id}/destino/${this.productoGestion.destino_id}`,data);
                this.productos.map(producto=>{
                    if (producto.producto_id == this.productoGestion.id && producto.bodega_id == this.productoGestion.origen_id) {
                        producto.cantidad-=this.productoGestion.cantidad;
                    }
                     if (producto.producto_id == this.productoGestion.id && producto.bodega_id == this.productoGestion.destino_id) {
                       producto.cantidad+= Number(this.productoGestion.cantidad);
                    }
                    
                });
            } catch (error) {
                this.error=error;
                console.log(error.response);
                
            }
        },
        makePaginate(productos){
            let perPage=this.perPage;
            let currentPage=this.currentPage;
            let from= (perPage*currentPage) - perPage;
            let to= perPage*currentPage;
            return productos.slice(from,to);

        },
        makePages(){
            let cantidad = Math.ceil(this.productos.length / this.perPage);
            for (let x = 1; x <=cantidad; x++) {
               this.pages.push(x);
            }
            console.log(this.pages);
            
        }
    },
    created(){
        axios.get('/productos').then(res=>{ this.productos=res.data; console.log(res.data);
        }).catch(err=>{ console.log(err); });

        axios.get('/bodegas').then(res=>{this.bodegas=res.data; }).catch(err=>{ console.log(err.data); });
        
    },
    computed:{
        productosPaginados(){
            return this.makePaginate(this.productos)
        }    
    },
    watch:{
        productos(){
            this.makePages();
        }
    }
}
</script>