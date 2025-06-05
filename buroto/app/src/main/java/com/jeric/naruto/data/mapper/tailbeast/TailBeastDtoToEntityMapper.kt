package com.jeric.naruto.data.mapper.tailbeast

import com.jeric.naruto.data.local.entity.TailedBeastEntity
import com.jeric.naruto.data.remote.dto.tailbeast.TailedBeastDto
import com.jeric.naruto.domain.model.tail_beast.TailedBeastModel

fun TailedBeastDto.toEntity(): TailedBeastEntity {
    return TailedBeastEntity(
        id = this.id.toString(),
        name = this.name,
//        images = this.images,
//        jutsu = this.jutsu,
//        natureType = this.natureType,
//        tools = this.tools,
//        uniqueTraits = this.uniqueTraits
    )
}


fun List<TailedBeastDto>.toEntityList(): List<TailedBeastEntity> {
    return this.map { it.toEntity() }
}

fun List<TailedBeastDto>.toModelList(): List<TailedBeastModel> {
    return this.map { it.toDomainModel() }
}


