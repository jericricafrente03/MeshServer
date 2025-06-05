package com.jeric.ricafrente.naruto.core.database.dao

import com.jeric.ricafrente.naruto.core.database.Akatsuki_table
import com.jeric.ricafrente.naruto.core.database.Kara_table
import com.jeric.ricafrente.naruto.core.database.NarutoKMP
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.utils.toJoinedString
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.flow
import kotlinx.coroutines.flow.flowOn
import kotlinx.coroutines.withContext

class KaraDao(
    private val karaDatabase: NarutoKMP
) {
    private val query get() = karaDatabase.karaEntityQueries

    fun getAllKara(): Flow<List<Kara_table>> = flow {
        emit(query.selectAllKara().executeAsList())
    }.flowOn(Dispatchers.IO)

    suspend fun selectOneById(id: String) = withContext(Dispatchers.IO) {
        query.selectKaraById(id = id).executeAsOne()
    }

    suspend fun insertKara(karaModel: KaraModel) = withContext(Dispatchers.IO) {
        query.insertKara(
            id = karaModel.id,
            name = karaModel.name,
            images = karaModel.images.toJoinedString(),
            jutsu = karaModel.jutsu.toJoinedString(),
            natureType = karaModel.natureType.toJoinedString(),
            tools = karaModel.tools.toJoinedString(),
            father = karaModel.father,
            mother = karaModel.mother,
            brother = karaModel.brother,
            daughter = karaModel.daughter,
            wife = karaModel.wife,
            birthdate = karaModel.birthdate,
            bloodType = karaModel.bloodType,
            sex = karaModel.sex
        )
    }

    suspend fun deleteKara() = withContext(Dispatchers.IO) {
        query.deleteAllKara()
    }

}