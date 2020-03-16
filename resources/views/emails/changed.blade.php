<h2>Hola {{$user->name}}</h2>
<br>
<p>has cambiado tu correo electronico por favor confirma tu cuenta en el siguiente enlace:</p>

{{route('verify',$user->verification_token)}}
