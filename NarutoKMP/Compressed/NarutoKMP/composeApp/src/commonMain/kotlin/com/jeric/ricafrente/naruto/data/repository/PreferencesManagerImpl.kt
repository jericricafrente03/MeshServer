package com.jeric.ricafrente.naruto.data.repository

import androidx.datastore.core.DataStore
import androidx.datastore.preferences.core.Preferences
import androidx.datastore.preferences.core.edit
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.catch
import kotlinx.coroutines.flow.map
import kotlinx.coroutines.withContext
import kotlinx.coroutines.CancellationException
import kotlinx.coroutines.flow.flowOn
import kotlinx.io.IOException

class PreferencesManagerImpl(private val dataStore: DataStore<Preferences>): PreferenceManager {

    private object PreferencesKey {
        val onBoardingKey = androidx.datastore.preferences.core.booleanPreferencesKey(name = "on_boarding_completed")
    }

    override suspend fun saveOnBoardingState(completed: Boolean) {
        return withContext(Dispatchers.IO){
            try {
                dataStore.edit {
                    it[PreferencesKey.onBoardingKey] = completed
                }
            }catch (e: Exception){
                if (e is CancellationException) throw e
                e.printStackTrace()
            }
        }
    }

    override fun readOnBoardingState(): Flow<Boolean> {
        return dataStore.data
            .catch { exception ->
                if (exception is IOException) {
                    emit(androidx.datastore.preferences.core.emptyPreferences())
                } else {
                    throw exception
                }
            }
            .map { preferences ->
                val onBoardingState = preferences[PreferencesKey.onBoardingKey] ?: false
                onBoardingState
            }.flowOn(Dispatchers.IO)
    }
}