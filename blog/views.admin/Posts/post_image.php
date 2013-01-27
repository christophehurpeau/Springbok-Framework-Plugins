<img src="<? HHtml::staticUrl('/files/library/'.$image->image_id.'-small.jpg') ?>" width="75" height="75" class="floatL mr10"/>
{link 'Sélectionner une autre image','#',array('onclick'=>'return _.posts.selectImage('.$id.')')}<br />
<br />
Dans le texte : <input id="imageInTextYes" name="imageInText" type="radio" value="1"{if $image->isInText()} checked="checked"{/if}/> <label for="imageInTextYes">Oui </label>
 &nbsp; <input id="imageInTextNo" name="imageInText" type="radio" value="0"{if !$image->isInText()} checked="checked"{/if}/> <label for="imageInTextNo">Non</label>
