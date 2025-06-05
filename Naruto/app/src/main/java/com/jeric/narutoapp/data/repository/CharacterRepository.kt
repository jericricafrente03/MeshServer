package com.jeric.narutoapp.data.repository

import androidx.paging.ExperimentalPagingApi
import androidx.paging.Pager
import androidx.paging.PagingConfig
import androidx.paging.PagingData
import com.jeric.narutoapp.data.api.NarutoApi
import com.jeric.narutoapp.data.local.NarutoDatabase
import com.jeric.narutoapp.data.model.NarutoCharacter
import com.jeric.narutoapp.data.paging.CharacterRemoteMediator
import kotlinx.coroutines.flow.Flow

class CharacterRepository(
    private val api: NarutoApi,
    private val db: NarutoDatabase
) {
    @OptIn(ExperimentalPagingApi::class)
    fun getCharacters(): Flow<PagingData<NarutoCharacter>> {
        return Pager(
            config = PagingConfig(
                pageSize = 20,
                enablePlaceholders = false
            ),
            remoteMediator = CharacterRemoteMediator(api, db),
            pagingSourceFactory = { db.characterDao().getAllCharacters() }
        ).flow
    }
} 