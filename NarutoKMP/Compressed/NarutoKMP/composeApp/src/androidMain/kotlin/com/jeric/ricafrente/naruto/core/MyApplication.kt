package com.jeric.ricafrente.naruto.core

import android.app.Application
import com.jeric.ricafrente.naruto.core.di.initKoin
import org.koin.android.ext.koin.androidContext

class MyApplication : Application() {
    override fun onCreate() {
        super.onCreate()
        initKoin(enableNetworkLogs = true) {
            androidContext(applicationContext)
        }
    }
}