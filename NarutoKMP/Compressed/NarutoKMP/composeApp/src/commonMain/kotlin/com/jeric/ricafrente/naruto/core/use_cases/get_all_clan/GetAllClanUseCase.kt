package com.jeric.ricafrente.naruto.core.use_cases.get_all_clan

import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import kotlinx.coroutines.flow.Flow

class GetAllClanUseCase(
    private val repository: RemoteDataSource
) {
    operator fun invoke(): Flow<DataState<List<ClanModel>>> {
        return repository.getAllClan()
    }
}