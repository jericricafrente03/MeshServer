package com.jeric.ricafrente.naruto.data.mapper.tailedbeast

import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.network.model.tailbeast.TailedBeastDto

fun TailedBeastDto.toDomainModel(): TailedBeastModel {
    return TailedBeastModel(
        id = this.id,
        name = this.name,
        images = this.images,
        jutsu = this.jutsu,
        tools = this.tools,
        uniqueTraits = this.uniqueTraits,
        natureType = this.natureType
    )
}

fun List<TailedBeastDto>.toTailedBeastModels(): List<TailedBeastModel> {
    return this.map { it.toDomainModel() }
}
