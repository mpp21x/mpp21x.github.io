---
title: LeetCode(6) - ZigZag Conversion 
date: 2020-05-28 22:08:42
tags:
- leetcode
- javascript
- algorithm
- java
img: /medias/page_images/2020/leetcode-6-ZigZag-Conversion.png
categories: leetcode algorithms

---
* link：[6. ZigZag Conversion](https://leetcode.com/problems/zigzag-conversion/submissions/)
* difficulty：`medium`
* language：`javascript` , `java`

## 題目原文

The string `"PAYPALISHIRING"` is written in a zigzag pattern on a given number of rows like this: (you may want to display this pattern in a fixed font for better legibility)


```
P   A   H   N
A P L S I I G
Y   I   R
```

And then read line by line: `"PAHNAPLSIIGYIR"`

Write the code that will take a string and make this conversion given a number of rows:

```
string convert(string s, int numRows);
```

Example 1:

```
Input: s = "PAYPALISHIRING", numRows = 3
Output: "PAHNAPLSIIGYIR"
```

Example 2:

```
Input: s = "PAYPALISHIRING", numRows = 4
Output: "PINALSIGYAHRPI"
Explanation:

P     I    N
A   L S  I G
Y A   H R
P     I
```

### 題目解釋

給予兩個參數：字串 `s` 以及整數 `numRows`，按照 `numRowss` 的數字，從字串每個一個字元開始進行一個蜿蜒的排序，以下舉例：

當 `s` 為 `abcdefgh'`，`numRowss` 為 `3` 時，排序如下：

```
a   e
b d f
c   g
```

當 `s` 為 `abcdefg'`，`numRowss` 為 `4` 時，排序如下：

```
a     g
b   f h
c e   i
d
```

題目需要我們以上述的排序，由上而下，由左至右取得另一個排列組合的字串，以上述的違例的答案就會是：

```
a     g // 取 ag
b   f h // 取得 agbfh
c e   i // 取得 agbfhcei
d       // 取得 agbfhceie
``` 

最後的答案就是 `agbfhceie`。

## 解題思路

為方便說明以及避免混搖幾個很相近但不同的概念(其實也是自己不清楚的部分)，針對幾個項目說明一下：

* 本文所提到的 **行** 代表直的， **列** 代表橫的。

```
  *    *
  *    *
  *    *
第1行 第2行  ...
```

```
* * * 第1列
* * * 第2列
...
```

### 解法一：遍歷

現在我們嘗試用上述的這些規律來完成題目，過程如下：
* 首先要做的便是先按照 `numRows` 其排序規律開始進行排序，過程使用迴圈將字元一一取出。
* 取出的同時，判斷現在正處於哪一列，並將其儲存到一變數稱為 `map`， `key` 值為其所在的列數。
* 取出後，再將 `map` 陣列元素組成一個字串回傳即可。

以下面這個例子來說明：

* `s` 為 `PAYPALISHIRING`
* `numRows` 為 3

```
P   A   H   N
A P L S I I G
Y   I   R
```

我們執行的步驟為：

```
P(存入 map, 其 key 為0) 
A(存入 map, 其 key 為1) 
Y(存入 map, 其 key 為2) 
P(存入 map, 其 key 為1) 
A(存入 map, 其 key 為0) 
L(存入 map, 其 key 為1) 
I(存入 map, 其 key 為2)
...
```

將執行步驟畫成圖，流程如下：

![解法一的流程](first-way.png)

最後再將 map 由 0 開始遍歷取出組成字串即可。

這個算法的時間複雜度為：`O(n)`，實際步驟大致為 `s` + `n`。

* `s` 代表一開始遞迴取出所有字元的步驟總數。
* `n` 代表最後將 `map` 每個元素取出後，組成一個字串的步驟，其操作步驟為等於同 `numRows`，也就是行高。

### 解法二：按列直接組字串，總步驟為 m

這是目前想到最好的做法，這個做法的概念主要改良解法一，為了去掉最後遞迴組字串的 n 步驟，只是這方法只有在使用 Java 實作時，執行速度和記憶體用量才有明顯優化，使用 JavaScript 沒什麼變化。

具體的做法是在遞迴 `s` 字串時，通過運算直接指向同列的下一個字串，用下面這個來說明：

* `s` 為 `PAYPALISHIRING`
* `numRows` 為 4

```
P     I    N
A   L S  I G
Y A   H R
P     I
```

我們嘗試將上述的字串改為對應在 `s` 的索引值的話，排序如下：

```
0     6       12
1   5 7    11 13
2 4   8 10    14
3     9
```

同樣是遍歷取出字串，只是這次我們的順序調整為：由左而右，由上而下取出字串。如下所示，我們所取出的字元其對應的索引順序如下：

```
0
6
12
1
5
7
11
...
```

將執行步驟畫成圖，流程如下：

![](second-way.png)

接下來我們要做的，便是將其規律歸納出一個函式，計算出對應在字串 `s` 裡面的字元索引值。

### 列處於最上方或最下列的公式

當列處於最上方的列或最下方的列，計算 $i$ 的基本上公式為：$c(2n - 2)$。

* $n$ 代表 `numRows`
* $c$ 代表行的執行次數。

### 列處於中間的公式

當列處於中間列的時候，該如何計算 $i$ ？這時候我們可以利用上面的字串對應索引的圖來找出規律，先觀察下方的第二列。

```
1   5 7    11 13 // 第二列
...
```

仔細觀察，`1,5,7,11` 這一列，不難發現其規律為：
* 兩個數為一個組合，為什麼？例如 `1,5` 或者是 `7,11`，因為遞增數皆共同為 `6`。
    * 這個 `6` 跟前面提到的 $2n - 2$ 是一樣的。 
* 第一個組合：`1,5` 到下一步為 `7,11`，首先分前後來說明，這裡的 `1,5` 當中的 `1` 和  `5`，我們分開來說明如何計算）。
    * `1` : `1` 代表其所在列數，之後往右邊移動時，每次遞增皆增加 $2n-2$
    * `5` : 上述的計算結果 `l` 再加上 `numRows` 即可。

接下來只要將此公式套用在遞迴當中即可完成。

## 實作 JavaScript

### 解法一

以下程式碼為解法一的實作程式碼：

```javascript
var convert = function (str, numRows) {
    const map = [];
    let rowIndex = 0, addNum = numRows > 1 ? -1 : 0;
    for (let char of str) {
        map[rowIndex] = map.hasOwnProperty(rowIndex) ? map[rowIndex] + `${char}` : char;
        if (rowIndex === (numRows - 1) || (rowIndex === 0)) {
            addNum *= -1;
        }
        rowIndex += addNum;
    }
    return map.join('');
};
```

### 解法二

在 JavaScript 使用解法二的方式，執行速度和記憶體用量沒什麼明顯的提升。

```javascript
var convert = function (str, numRows) {
    if(numRows < 2){
        return str;
    }
    
    const strLength = str.length;
    const cycleNum = (2 * numRows - 2);
    let answer = '';
    for (let rowIndex = 0; rowIndex < numRows; rowIndex++) {
        for (let colIndex = 0; (colIndex + rowIndex) < strLength; colIndex += cycleNum) {
            answer += str[rowIndex + colIndex];
            if (rowIndex !== 0 && rowIndex !== (numRows - 1) && ((colIndex + cycleNum) - rowIndex) < strLength) {
                answer += str[(colIndex + cycleNum) - rowIndex];
            }
        }
    }
    return answer;
};
```

## 實作 java

### 解法一    
    
```java
import java.text.CharacterIterator;
import java.text.StringCharacterIterator;
import java.util.HashMap;

class Solution {
    public String convert(String s, int numRows) {
        HashMap<Integer, String> map = new HashMap<Integer, String>();
        int rowIndex = 0, addNum = numRows > 1 ? -1 : 0;

        CharacterIterator it = new StringCharacterIterator(s);
        while (it.current() != CharacterIterator.DONE) {
            if (map.containsKey(rowIndex)) {
                map.put(rowIndex, map.get(rowIndex).concat(String.valueOf(it.current())));
            } else {
                map.put(rowIndex, String.valueOf(it.current()));
            }
            if (rowIndex == (numRows - 1) || rowIndex == 0) {
                addNum *= -1;
            }
            rowIndex += addNum;
            it.next();
        }


        return String.join("", map.values());
    }
}
```

### 解法二

```java
class Solution {
    public String convert(String s, int numRows) {

        if (numRows == 1) return s;

        StringBuilder ret = new StringBuilder();
        int n = s.length();
        int cycleLen = 2 * numRows - 2;

        for (int i = 0; i < numRows; i++) {
            for (int j = 0; j + i < n; j += cycleLen) {
                ret.append(s.charAt(j + i));
                if (i != 0 && i != numRows - 1 && j + cycleLen - i < n)
                    ret.append(s.charAt(j + cycleLen - i));
            }
        }
        return ret.toString();
    }
}
```

這兩個在 leetcode 的實際執行時間如下兩張圖：

![解法一](java_first_solution.png)

![解法二](java_second_solution.png)
