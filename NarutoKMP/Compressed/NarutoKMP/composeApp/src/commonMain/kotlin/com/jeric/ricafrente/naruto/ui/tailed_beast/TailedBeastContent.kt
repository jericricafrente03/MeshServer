package com.jeric.ricafrente.naruto.ui.tailed_beast

import androidx.compose.animation.core.animateDpAsState
import androidx.compose.animation.core.animateFloatAsState
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.WindowInsets
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.navigationBarsPadding
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.statusBars
import androidx.compose.foundation.layout.windowInsetsPadding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Close
import androidx.compose.material3.BottomSheetScaffold
import androidx.compose.material3.BottomSheetScaffoldState
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.SheetValue
import androidx.compose.material3.Text
import androidx.compose.material3.rememberBottomSheetScaffoldState
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.alpha
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import coil3.compose.AsyncImage
import coil3.compose.LocalPlatformContext
import coil3.request.ImageRequest
import coil3.request.crossfade
import com.jeric.ricafrente.naruto.ui.tailed_beast.components.InfoBox
import com.jeric.ricafrente.naruto.ui.tailed_beast.components.OrderedList
import com.jeric.ricafrente.naruto.ui.theme.titleColor
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.ic_bolt
import narutokmp.composeapp.generated.resources.ic_calendar
import narutokmp.composeapp.generated.resources.ic_logo
import narutokmp.composeapp.generated.resources.ic_placeholder
import narutokmp.composeapp.generated.resources.onboarding_3
import narutokmp.composeapp.generated.resources.power
import org.jetbrains.compose.resources.painterResource

@ExperimentalMaterial3Api
@Composable
fun TailedBeastContent(
    onBack: () -> Unit,
    id: Int,
    name: String,
    images: List<String>?,
    jutsu: List<String>?,
    natureType: List<String>?,
    tools: List<String>?,
    uniqueTraits: List<String>?,
) {
    val scaffoldState = rememberBottomSheetScaffoldState()

    val currentSheetFraction = scaffoldState.currentSheetFraction

    val radiusAnim by animateDpAsState(
        targetValue = if (currentSheetFraction == 1f) 40.dp
        else 0.dp,
        label = "Radius Animation"
    )

    BottomSheetScaffold(
        sheetShape = RoundedCornerShape(
            topStart = radiusAnim,
            topEnd = radiusAnim
        ),
        sheetDragHandle = null,
        scaffoldState = scaffoldState,
        sheetPeekHeight = 138.dp,
        sheetContent = {
            BottomSheetContent(
                id = id,
                name = name,
                natureType = natureType,
                jutsu = jutsu,
                uniqueTraits = uniqueTraits,
                tools = tools
            )
        },
        content = {
            BackgroundContent(
                imageFraction = currentSheetFraction,
                onCloseClicked = { onBack() },
                images = images
            )
        }
    )
}


@Composable
fun BackgroundContent(
    imageFraction: Float = 1f,
    images: List<String>?,
    backgroundColor: Color = MaterialTheme.colorScheme.surface,
    onCloseClicked: () -> Unit
) {

    val animatedImageSize by animateFloatAsState(
        targetValue = imageFraction,
        label = "Radius Animation"
    )


    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(backgroundColor)
    ) {
        val imageSource = remember(images) {
            images?.lastOrNull().takeIf { data -> !data.isNullOrBlank() }
                ?: Res.drawable.onboarding_3
        }
        AsyncImage(
            modifier = Modifier
                .fillMaxWidth()
                .fillMaxHeight(
                    fraction = (animatedImageSize + 0.4f)
                        .coerceAtMost(1.0f)
                )
                .align(Alignment.TopCenter),
            model = ImageRequest.Builder(LocalPlatformContext.current)
                .data(imageSource)
                .crossfade(true)
                .build(),
            placeholder = painterResource(Res.drawable.ic_placeholder),
            error = painterResource(Res.drawable.ic_placeholder),
            contentDescription = null,
            contentScale = ContentScale.Crop,
        )

        Row(
            modifier = Modifier
                .fillMaxWidth()
                .windowInsetsPadding(WindowInsets.statusBars),
            horizontalArrangement = Arrangement.End
        ) {
            IconButton(
                modifier = Modifier.padding(all = 10.dp),
                onClick = { onCloseClicked() }
            ) {
                Icon(
                    modifier = Modifier.size(32.dp),
                    imageVector = Icons.Default.Close,
                    contentDescription = null,
                    tint = titleColor
                )
            }
        }
    }
}


@OptIn(ExperimentalMaterial3Api::class)
val BottomSheetScaffoldState.currentSheetFraction: Float
    get() {
        val targetValue = bottomSheetState.targetValue
        val currentValue = bottomSheetState.currentValue

        return when {
            currentValue == SheetValue.Hidden && targetValue == SheetValue.Hidden -> 1f
            currentValue == SheetValue.Expanded && targetValue == SheetValue.Expanded -> 0f
            currentValue == SheetValue.Hidden && targetValue == SheetValue.Expanded -> 1f
            currentValue == SheetValue.Expanded && targetValue == SheetValue.Hidden -> 0f
            else -> 1f
        }
    }


fun calculatePower(
    jutsu: List<String>?,
    natureType: List<String>?,
    tools: List<String>?,
    uniqueTraits: List<String>?
): Int {
    val jutsuCount = jutsu?.size ?: 0
    val natureCount = natureType?.size ?: 0
    val toolsCount = tools?.size ?: 0
    val traitsCount = uniqueTraits?.size ?: 0

    val rawPower = (jutsuCount * 10) +
            (natureCount * 15) +
            (toolsCount * 5) +
            (traitsCount * 20)

    return rawPower.coerceIn(60, 100)
}

fun calculateBijudamaPower(jutsu: List<String>?, natureType: List<String>?, uniqueTraits: List<String>?): Int {
    val jutsuCount = jutsu?.size ?: 0
    val natureCount = natureType?.size ?: 0
    val traitsCount = uniqueTraits?.size ?: 0
    val rawPower = (jutsuCount * 10) +
            (natureCount * 20) +
            (traitsCount * 25)

    return rawPower.coerceIn(80, 150)
}

fun getRandomBirthMonth(): String {
    val months = listOf(
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    )
    return months.random().take(3)
}

fun getShortDescription(jutsu: List<String>?, natureType: List<String>?, tools: List<String>?, name: String): String {
    val jutsuInfo = jutsu?.joinToString(", ") ?: "an unknown set of jutsu techniques"
    val natureInfo = natureType?.joinToString(", ") ?: "an unidentified chakra nature"
    val traitsInfo = tools?.joinToString(", ") ?: "no distinctive or notable traits"

    return "$name is a formidable tailed beast known to utilize $jutsuInfo. " +
            "It possesses chakra nature(s) such as $natureInfo, which contribute to its unique abilities. " +
            "Among its defining characteristics, it is recognized for $traitsInfo. " +
            "These elements combine to make $name one of the most remarkable creatures in the shinobi world."
}


@Composable
fun BottomSheetContent(
    id: Int,
    name: String,
    natureType: List<String>?,
    jutsu: List<String>?,
    uniqueTraits: List<String>?,
    tools: List<String>?,
    infoBoxIconColor: Color = MaterialTheme.colorScheme.primary,
    contentColor: Color = titleColor
) {
    val shortBio = remember(name) {
        getShortDescription(
            jutsu = jutsu,
            natureType = natureType,
            tools = tools,
            name = name
        )
    }

    val power = remember(natureType) {
        calculatePower(
            jutsu = jutsu,
            natureType = natureType,
            tools = tools,
            uniqueTraits = uniqueTraits
        )
    }

    val bijudama = remember(jutsu) {
        calculateBijudamaPower(
            jutsu = jutsu,
            natureType = natureType,
            uniqueTraits = uniqueTraits
        )
    }

    val birthMonth = remember {
        getRandomBirthMonth()
    }

    Column(
        modifier = Modifier
            .padding(all = 20.dp)
    ) {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .padding(bottom = 20.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Icon(
                modifier = Modifier
                    .size(32.dp)
                    .weight(2f),
                painter = painterResource(resource = Res.drawable.ic_logo),
                contentDescription = null,
                tint = contentColor
            )
            Text(
                modifier = Modifier
                    .weight(8f),
                text = name,
                color = contentColor,
                fontSize = MaterialTheme.typography.titleLarge.fontSize,
                fontWeight = FontWeight.Bold
            )
        }
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .padding(bottom = 16.dp),
            horizontalArrangement = Arrangement.SpaceBetween
        ) {
            InfoBox(
                icon = Res.drawable.ic_bolt,
                iconColor = infoBoxIconColor,
                bigText = power.toString(),
                smallText = "Power",
                textColor = contentColor
            )
            InfoBox(
                icon = Res.drawable.power,
                iconColor = infoBoxIconColor,
                bigText = bijudama.toString(),
                smallText = "Rankings",
                textColor = contentColor
            )
            InfoBox(
                icon = Res.drawable.ic_calendar,
                iconColor = infoBoxIconColor,
                bigText = birthMonth,
                smallText = "Birthday",
                textColor = contentColor
            )
        }
        Text(
            modifier = Modifier.fillMaxWidth(),
            text = "About",
            color = contentColor,
            fontSize = MaterialTheme.typography.bodyMedium.fontSize,
            fontWeight = FontWeight.Bold
        )
        Text(
            modifier = Modifier
                .alpha(0.5f)
                .padding(bottom = 16.dp),
            text = shortBio,
            color = contentColor,
            fontSize = MaterialTheme.typography.bodyMedium.fontSize,
            maxLines = 7
        )
        val abilities = if (id == 771) listOf(
            "Water",
            "Fire",
            "Wind",
            "Earth"
        ) else jutsu
        OrderListRow(
            contentColor = contentColor,
            type = natureType,
            abilities = abilities
        )
    }
}

@Composable
fun OrderListRow(contentColor: Color, type: List<String>?, abilities: List<String>?) {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .navigationBarsPadding(),
        horizontalArrangement = Arrangement.SpaceBetween
    ) {

        OrderedList(
            title = "Family",
            items = listOf("Kazekage", "Hokage"),
            textColor = contentColor
        )

        OrderedList(
            title = "Abilities",
            items = abilities ?: listOf("Fire", "Bijudama","Water","Lightning"),
            textColor = contentColor
        )

        OrderedList(
            title = "Nature Types",
            items = type ?: listOf("Water", "Poison","Fire","Earth"),
            textColor = contentColor
        )

    }
}