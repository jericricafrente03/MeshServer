package com.jeric.naruto.domain.repository

import androidx.paging.PagingData
import com.jeric.naruto.domain.model.character.CharacterModel
import com.jeric.naruto.domain.model.tail_beast.TailedBeastModel
import kotlinx.coroutines.flow.Flow

interface RemoteDataSource {
    fun getAllHeroes(): Flow<PagingData<CharacterModel>>
//    fun getAllTailBeast(): Flow<List<TailedBeastModel>>
}