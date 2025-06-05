package com.jeric.ricafrente.naruto.core.database.dao

import com.jeric.ricafrente.naruto.core.database.Akatsuki_table
import com.jeric.ricafrente.naruto.core.database.Clan_table
import com.jeric.ricafrente.naruto.core.database.NarutoKMP
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.utils.toJoinedString
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.flow
import kotlinx.coroutines.flow.flowOn
import kotlinx.coroutines.withContext

class AkatsukiDao(
    private val akatsukiDatabase: NarutoKMP
) {
    private val query get() = akatsukiDatabase.akatsukiEntityQueries

    fun getAllAkatsuki(): Flow<List<Akatsuki_table>> = flow {
        emit(query.selectAllAkatsuki().executeAsList())
    }.flowOn(Dispatchers.IO)

    suspend fun selectOneById(id: String) = withContext(Dispatchers.IO) {
        query.selectAkatsukiById(id = id).executeAsOne()
    }

    suspend fun insertAkatsuki(akatsukiModel: AkatsukiModel) = withContext(Dispatchers.IO) {
        query.insertAkatsuki(
            id = akatsukiModel.id,
            name = akatsukiModel.name,
            images = akatsukiModel.images.toJoinedString(),
            jutsu = akatsukiModel.jutsu.toJoinedString(),
            natureType = akatsukiModel.natureType.toJoinedString(),
            tools = akatsukiModel.tools.toJoinedString(),
            father = akatsukiModel.father,
            mother = akatsukiModel.mother,
            brother = akatsukiModel.brother,
            daughter = akatsukiModel.daughter,
            wife = akatsukiModel.wife,
            birthdate = akatsukiModel.birthdate,
            bloodType = akatsukiModel.bloodType,
            sex = akatsukiModel.sex
        )
    }

    suspend fun deleteAkatsuki() = withContext(Dispatchers.IO) {
        query.deleteAllAkatsuki()
    }

}