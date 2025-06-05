package com.jeric.ricafrente.naruto.core.database.dao

import com.jeric.ricafrente.naruto.core.database.Clan_table
import com.jeric.ricafrente.naruto.core.database.NarutoKMP
import com.jeric.ricafrente.naruto.core.database.TailedBeastEntity
import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.utils.toJoinedInt
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.flow
import kotlinx.coroutines.flow.flowOn
import kotlinx.coroutines.withContext

class ClanDao(
    private val clanDatabase: NarutoKMP
) {
    private val query get() = clanDatabase.clanEntityQueries

    fun getAllClan(): Flow<List<Clan_table>> = flow {
        emit(query.selectAllClan().executeAsList())
    }.flowOn(Dispatchers.IO)

    suspend fun selectOneById(id: String) = withContext(Dispatchers.IO) {
        query.selectClanById(id = id).executeAsOne()
    }

    suspend fun insertClan(clanModel: ClanModel) = withContext(Dispatchers.IO) {
        query.insertClan(
            id = clanModel.id,
            name = clanModel.name,
            characters = clanModel.characters.toJoinedInt()
        )
    }

    suspend fun deleteClan() = withContext(Dispatchers.IO) {
        query.deleteAllClan()
    }

}