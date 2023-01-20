<div class="container">
    <div class="smallContainer" id="checkboxes">
    </div>
    <hr>
    <h2> Prix </h2>
    <ul>
        <div class="prix">
            <div>
                <input type="radio" id="0" name="prixSelected" value="0" style="display:none" onclick="showItems()"
                       checked>
                <label for="0">Tous les prix</label>
            </div>

            <div>
                <input type="radio" id="1" name="prixSelected" value="1" style="display:none" onclick="showItems()">
                <label for="1">Moins de 10€</label>
            </div>

            <div>
                <input type="radio" id="2" name="prixSelected" value="2" style="display:none" onclick="showItems()">
                <label for="2">Entre 10 et 20€</label>
            </div>

            <div>
                <input type="radio" id="3" name="prixSelected" value="3" style="display:none" onclick="showItems()">
                <label for="3">Entre 20 et 50€</label>
            </div>

            <div>
                <input type="radio" id="4" name="prixSelected" value="4" style="display:none" onclick="showItems()">
                <label for="4">Entre 50 et 100€</label>
            </div>

            <div>
                <input type="radio" id="5" name="prixSelected" value="5" style="display:none" onclick="showItems()">
                <label for="5">Entre 100 et 500€</label>
            </div>

            <div>
                <input type="radio" id="6" name="prixSelected" value="6" style="display:none" onclick="showItems()">
                <label for="6">Plus de 500€</label>
            </div>
        </div>
    </ul>
</div>