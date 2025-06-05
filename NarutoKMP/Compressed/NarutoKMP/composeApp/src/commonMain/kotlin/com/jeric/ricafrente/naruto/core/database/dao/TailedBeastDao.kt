package com.jeric.ricafrente.naruto.core.database.dao

import app.cash.sqldelight.coroutines.asFlow
import app.cash.sqldelight.coroutines.mapToList
import com.jeric.ricafrente.naruto.core.database.NarutoKMP
import com.jeric.ricafrente.naruto.core.database.TailedBeastEntity
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.utils.toJoinedString
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.flow
import kotlinx.coroutines.flow.flowOn
import kotlinx.coroutines.withContext

class TailedBeastDao(
    private val tailedBeastDatabase: NarutoKMP
) {
    private val query get() = tailedBeastDatabase.tailedBeastEntityQueries

    fun getAllTailedBeastFlow(): Flow<List<TailedBeastEntity>> = flow {
        emit(query.getAllTailBeasts().executeAsList())
    }.flowOn(Dispatchers.IO)

    suspend fun selectOneById(id: String) = withContext(Dispatchers.IO) {
        query.getTailBeastById(id = id.toLong()).executeAsOne()
    }

    suspend fun insert(tailedBeastEntity: TailedBeastModel) = withContext(Dispatchers.IO) {
        query.insertTailBeast(
            id = tailedBeastEntity.id.toLong(),
            name = tailedBeastEntity.name,
            images = tailedBeastEntity.images.toJoinedString(),
            jutsu = tailedBeastEntity.jutsu.toJoinedString(),
            tools = tailedBeastEntity.tools.toJoinedString(),
            uniqueTraits = tailedBeastEntity.uniqueTraits.toJoinedString(),
            natureType = tailedBeastEntity.tools.toJoinedString()
        )
    }



    suspend fun deleteAllTailedBeast() = withContext(Dispatchers.IO) {
        query.deleteAllTailBeasts()
    }

}