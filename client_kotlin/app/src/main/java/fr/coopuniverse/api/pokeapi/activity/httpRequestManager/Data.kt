package fr.coopuniverse.api.pokeapi.activity.httpRequestManager

class Data(var user: Int, var money: Any? = null, var cards: Any? = null, var parameter: Any? = null) {

    override fun toString(): String {
        return "Money : $money Id User : $user Param :$parameter Cards:$cards"
    }
}