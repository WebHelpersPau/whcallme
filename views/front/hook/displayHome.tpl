{if isset($msg) && $msg neq ""}
<div class="{$type} msg-box">{$msg}</div>
{/if}

<div id="whcallme_frame">
	<div id="whcallme_cta">{$call_to_action}</div>
	<div id="whcallme_block">
		<form action="" method="POST">
			<h1>{$texte_accroche}</h1><br/>
			<div class="row">	
				<div class="col">
					<label for="nom">{l s="Nom" mod="whcallme"}*</label>
					<input type="text" name="nom" id="nom"/>
				</div>
				<div class="col">
					<label for="prenom">{l s="Prénom" mod="whcallme"}*</label>
					<input type="text" name="prenom" id="prenom"/>
				</div>
			</div>
			<div class="row">	
				<div class="col">
					<label for="tel">{l s="Tél" mod="whcallme"}*</label>
					<input type="text" name="tel" id="tel"/>
				</div>
				<div class="col">
					<label for="email">{l s="E-mail" mod="whcallme"}</label>
					<input type="text" name="email" id="email"/>
				</div>
			</div><br/>
			<div class="row rgpd_msg">
				<input type="checkbox" name="rgpd_ok" value="1"/> {l s="J'accepte que mes données personnelles soient stockées. J'ai bien lu et accepte les conditions générales de gestion/stockage de mes données." mod="whcallme"}<br/>
			</div>
			<input type="submit" name="btnCallMe" value="Rappelez-moi !"/>
		</form>
	</div>
</div>