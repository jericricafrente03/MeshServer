package com.jeric.ricafrente.naruto.data.mapper.tailedbeast

import com.jeric.ricafrente.naruto.core.database.TailedBeastEntity
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.utils.convertStringToList

fun TailedBeastEntity.toDomainModel(): TailedBeastModel {
    return TailedBeastModel(
        id = this.id.toInt(),
        name = this.name,
        images = this.images.convertStringToList(),
        jutsu = this.jutsu.convertStringToList(),
        tools = this.tools.convertStringToList(),
        uniqueTraits = this.uniqueTraits.convertStringToList(),
        natureType = this.natureType.convertStringToList()
    )
}


fun List<TailedBeastEntity>.fromEntityToModel(): List<TailedBeastModel> {
    return this.map { it.toDomainModel() }
}
