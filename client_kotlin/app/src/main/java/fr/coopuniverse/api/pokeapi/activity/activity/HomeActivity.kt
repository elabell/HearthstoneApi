package fr.coopuniverse.api.pokeapi.activity.activity

import android.os.Bundle
import android.support.design.widget.NavigationView
import android.support.v4.app.FragmentManager
import android.support.v4.app.FragmentTransaction
import android.support.v4.view.GravityCompat
import android.support.v7.app.ActionBarDrawerToggle
import android.support.v7.app.AppCompatActivity
import android.view.Menu
import android.view.MenuItem
import fr.coopuniverse.api.pokeapi.R
import fr.coopuniverse.api.pokeapi.activity.fragment.*
import kotlinx.android.synthetic.main.activity_home.*
import kotlinx.android.synthetic.main.app_bar_home.*

class HomeActivity : AppCompatActivity(), NavigationView.OnNavigationItemSelectedListener, CallBackFragment
{

    override fun setFragment(dest: Destination) {

        var fragment = when(dest)
        {
            Destination.Home -> HomeFragment()
            Destination.Market -> MarketFragment()
            Destination.Craft -> CraftFragment()
            Destination.Melt -> MeltFragment()
            Destination.Quizz -> QuizzFragment()
            Destination.Shop -> ShopFragment()
            Destination.Exchange -> HomeFragment()
            Destination.Settings -> SettingsFragment()
            Destination.Inventory -> InventoryFragment()
            Destination.CardDetail -> CardDetailFragment()
        }

        supportFragmentManager.beginTransaction().add(R.id.fragment_container,fragment).commit()
    }


    override fun onCreate(savedInstanceState: Bundle?)
    {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_home)
        setSupportActionBar(toolbar)


        val toggle = ActionBarDrawerToggle(
                this, drawer_layout, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close)
        drawer_layout.addDrawerListener(toggle)
        toggle.syncState()

        supportFragmentManager.beginTransaction().add(R.id.fragment_container, HomeFragment()).commit()


        nav_view.setNavigationItemSelectedListener(this)
    }

    override fun onBackPressed()
    {
        if (drawer_layout.isDrawerOpen(GravityCompat.START))
        {
            drawer_layout.closeDrawer(GravityCompat.START)
        }
        else
        {
            super.onBackPressed()
        }
    }

    override fun onCreateOptionsMenu(menu: Menu): Boolean
    {
        menuInflater.inflate(R.menu.home, menu)
        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean
    {
        when (item.itemId)
        {
            R.id.action_settings -> return true
            else -> return super.onOptionsItemSelected(item)
        }
    }

    override fun onNavigationItemSelected(item: MenuItem): Boolean
    {
        when (item.itemId)
        {
            R.id.nav_inventory ->
            {
                this.setFragment(Destination.Inventory)
            }
            R.id.nav_market ->
            {
                this.setFragment(Destination.Market)
            }
            R.id.nav_settings ->
            {
                this.setFragment(Destination.Settings)
            }
            R.id.nav_game ->
            {
                this.setFragment(Destination.Home)
            }
        }

        drawer_layout.closeDrawer(GravityCompat.START)
        return true
    }


}
