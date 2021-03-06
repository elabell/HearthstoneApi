package fr.coopuniverse.api.pokeapi.activity.fragment

import android.content.Context
import android.os.Bundle
import android.support.v4.app.Fragment
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup

import fr.coopuniverse.api.pokeapi.R
import fr.coopuniverse.api.pokeapi.activity.activity.CallBackFragment
import fr.coopuniverse.api.pokeapi.activity.activity.Destination
import kotlinx.android.synthetic.main.home_fragment.*

class HomeFragment : Fragment() {
    var callback: CallBackFragment? = null

    companion object {
        fun newInstance() = HomeFragment()
    }


    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        craftButton.setOnClickListener {
            callback?.setFragment(Destination.Market)
        }
        meltbutton.setOnClickListener {
            callback?.setFragment(Destination.Market)
        }
        quizzbutton.setOnClickListener {
            callback?.setFragment(Destination.Market)
        }

    }

    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        return inflater.inflate(R.layout.home_fragment, container, false)
    }


    override fun onActivityCreated(savedInstanceState: Bundle?) {
        super.onActivityCreated(savedInstanceState)


    }

    override fun onAttach(context: Context?) {
        super.onAttach(context)
        if (context is CallBackFragment) {
            callback = context
        }
    }

    override fun onDetach() {
        super.onDetach()
        callback = null
    }

}
