package com.jeric.naruto.data.repository

import androidx.paging.PagingData
import com.jeric.naruto.domain.model.character.CharacterModel
import com.jeric.naruto.domain.model.tail_beast.TailedBeastModel
import com.jeric.naruto.domain.repository.PreferenceManager
import com.jeric.naruto.domain.repository.RemoteDataSource
import kotlinx.coroutines.flow.Flow
import javax.inject.Inject

class RepositoryImpl @Inject constructor(
    private val remote: RemoteDataSource,
    private val dataStore: PreferenceManager
) {
    fun getAllCharacter(): Flow<PagingData<CharacterModel>> {
        return remote.getAllHeroes()
    }

//    fun getAllTailBeast(): Flow<List<TailedBeastModel>> {
//        return remote.getAllTailBeast()
//    }

    suspend fun saveOnBoardingState(completed: Boolean) {
        dataStore.saveOnBoardingState(completed = completed)
    }

    fun readOnBoardingState(): Flow<Boolean> {
        return dataStore.readOnBoardingState()
    }

}