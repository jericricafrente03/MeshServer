package com.jeric.ricafrente.naruto.ui.home_page

import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.lazy.LazyRow
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.unit.dp
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import androidx.compose.foundation.lazy.items
import androidx.compose.ui.platform.testTag
import com.jeric.ricafrente.naruto.ui.tailed_beast.components.ThumbnailItem

@Composable
fun TailedBeastList(
    modifier: Modifier = Modifier,
    itemList: List<TailedBeastModel>,
    onClickItem: (id: String) -> Unit
){
    LazyRow(
        contentPadding = PaddingValues(all = 10.dp),
        modifier = modifier
            .testTag("tailedBeastTag")
    ) {
        items(itemList, key = { it.id} ){ item ->
            ThumbnailItem(
                imageUrl = item.images ?: emptyList(),
                name = item.name,
                jutsu = item.jutsu ?: emptyList(),
                natureInfo = item.natureType ?: emptyList(),
                trailsInfo = item.uniqueTraits ?: emptyList(),
                onClick = { onClickItem(item.id.toString()) },
                modifier = Modifier.testTag(item.id.toString())
            )
        }

    }
}