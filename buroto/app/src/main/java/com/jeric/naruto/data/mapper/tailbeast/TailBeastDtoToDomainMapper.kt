package com.jeric.naruto.data.mapper.tailbeast

import com.jeric.naruto.data.remote.dto.tailbeast.TailedBeastDto
import com.jeric.naruto.domain.model.tail_beast.TailedBeastModel

fun TailedBeastDto.toDomainModel(): TailedBeastModel {
    return TailedBeastModel(
        id = this.id.toString(),
        name = this.name,
//        images = this.images,
//        jutsu = this.jutsu,
//        natureType = this.natureType,
//        tools = this.tools,
//        uniqueTraits = this.uniqueTraits
    )
}



