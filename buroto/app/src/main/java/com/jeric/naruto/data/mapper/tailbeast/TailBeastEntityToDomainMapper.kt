package com.jeric.naruto.data.mapper.tailbeast

import com.jeric.naruto.data.local.entity.TailedBeastEntity
import com.jeric.naruto.domain.model.tail_beast.TailedBeastModel

fun TailedBeastEntity.tailBeastEntityToDomain(): TailedBeastModel {
    return TailedBeastModel(
        id = this.id,
        name = this.name,
//        images = this.images,
//        jutsu = this.jutsu,
//        natureType = this.natureType,
//        tools = this.tools,
//        uniqueTraits = this.uniqueTraits
    )
}



