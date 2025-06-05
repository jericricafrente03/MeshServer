package com.jeric.narutoapp.data.paging

import androidx.paging.ExperimentalPagingApi
import androidx.paging.LoadType
import androidx.paging.PagingState
import androidx.paging.RemoteMediator
import androidx.room.withTransaction
import com.jeric.narutoapp.data.api.NarutoApi
import com.jeric.narutoapp.data.local.NarutoDatabase
import com.jeric.narutoapp.data.model.NarutoCharacter
import retrofit2.HttpException
import java.io.IOException

@OptIn(ExperimentalPagingApi::class)
class CharacterRemoteMediator(
    private val api: NarutoApi,
    private val db: NarutoDatabase
) : RemoteMediator<Int, NarutoCharacter>() {

    override suspend fun load(
        loadType: LoadType,
        state: PagingState<Int, NarutoCharacter>
    ): MediatorResult {
        return try {
            val loadKey = when (loadType) {
                LoadType.REFRESH -> 1
                LoadType.PREPEND -> return MediatorResult.Success(endOfPaginationReached = true)
                LoadType.APPEND -> {
                    val lastItem = state.lastItemOrNull()
                    if (lastItem == null) {
                        return MediatorResult.Success(endOfPaginationReached = true)
                    }
                    (state.lastItemOrNull()?.id?.toInt() ?: 1) + 1
                }
            }

            val response = api.getAllCharacters(page = loadKey)
            val characters = listOf(response)

            db.withTransaction {
                if (loadType == LoadType.REFRESH) {
                    db.characterDao().clearAll()
                }
                db.characterDao().insertAll(characters)
            }

            MediatorResult.Success(
                endOfPaginationReached = characters.isEmpty()
            )
        } catch (e: IOException) {
            MediatorResult.Error(e)
        } catch (e: HttpException) {
            MediatorResult.Error(e)
        }
    }
} 