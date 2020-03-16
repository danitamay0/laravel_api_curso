<h2>Hola {{$user->name}}</h2>
<br>
<p>gracias por crear tu cuenta, porfavor confirmala en el siguiente enlace</p>

{{route('verify',$user->verification_token)}}
