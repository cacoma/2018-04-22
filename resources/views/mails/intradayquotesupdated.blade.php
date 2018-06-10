Olá <i>administrador</i>,
<p>O comando {{ $log->command }} foi realizado.</p>
 
<p><u>Resultados:</u></p>
 
<div>
<p><b>Data da importacao:</b>&nbsp;{{ $log->dateUpdate }}</p>
<p><b>Importação com sucesso:</b>
<!--   &nbsp;{{ json_encode($log->success) }}</p> -->

         @foreach($log->success as $success)
          <ul>
              <li> {{$success}} </li>
          </ul>
         @endforeach
<br>
<p><b>Importação com falha:</b>
<!--   &nbsp;{{ json_encode($log->fail) }}</p> -->

         @foreach($log->fail as $fail)
          <ul>
              <li> {{$fail}} </li>
          </ul>
         @endforeach
<br>
<p><b>Data do email:</b>&nbsp;{{ $dateTimeEmail }}</p>
</div>

Att,
<br/>
<i>{{ config('app.name') }}</i>
