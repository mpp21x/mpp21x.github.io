---
title: LeetCode(1) - Two Sum (js/easy)
date: 2020-05-06 01:18:47
tags:
- leetcode
- javascript
- algorithm
- latex
img: /medias/page_images/2020/leetcode-1-two-sum_js.png
categories: leetcode algorithms
---
* link：[1. Two Sum](https://leetcode.com/problems/two-sum/)
* difficulty：`easy`
* language：`javascript`

## 題目原文

Given a binary tree, return the sum of values of its deepest leaves.
 

Example 1:


```
Given nums = [2, 7, 11, 15], target = 9,

Because nums[0] + nums[1] = 2 + 7 = 9,
return [0, 1].
```

### 題目解釋

給予一個數列 `nums` 以及一個指定的整數 `target`，我們必須找出在這個陣列中的某兩個數，其總和 必然與 `target` 相等，並將此二數的索引值放在一個陣列之後回傳出去。

另外，在陣列中，必然只有兩個數的總和跟 **指定的整數** 一樣，同時這兩個數必然是不同的數，不能為同一個數。

舉例來說：

`nums` 為 `[2, 7, 11, 15]`，而 `target` 為 `9`，因為 `nums[0]` 以及 `nums[1]` 總和為 `9` 跟 `target` 一樣，所以答案就是 `[0, 1]`。

## 解題思路

### 線索 1 `Target` 

首先我們知道，答案的兩個數總和（假設叫做 `a` 和 `b`），與 `target` 同等，透過簡單的推理可得知：

```
target = a + b
traget - a = b 
target - b = a 
```

我們可以透過 `target` 減去 `nums` 任一的數字，來得到另一個餘數，倘若此二數皆存在於 `nums` ，那就是我們想要找的答案 `a` 與 `b`。

所以答案必然是透過 `target` 進行加減的運算可得出。

### 線索 2 流程

由於要得到答案，流程必然經過這幾個步驟（數字為代稱，不一定為實際順序）：

1. **遍歷步驟**：也就是一一取出 nums 的每一個數字，這一步驟讓時間複雜度至少會是 `O(n)`。
2. **取得待確認值步驟**：取出 nums 的一個數字後，使用 `target` 減去此數的餘數，再來確認是否存在於 `nums`，即可。
3. **確認餘數是否存在於 nums **： 再來就是使用餘數，來確認是否存在於 `nums` ，且不能為同一 index，這個是這一題比較重要的環節，目前想到有兩種思路：
    
    3.1. **遍歷搜尋**  針對 `nums` 目前所運算到的位置之後的陣列，使用 `indexOf` 的方式來找出答案
    
    3.2 **暫存 map 搜尋** 將每次運算暫存於自定義的變數 `map`，搜尋時也一律從 `map` 當中搜尋。

再來針對我們的 3.1 和 3.2 這兩種搜尋做解釋。

* 3-1. 遍歷搜尋
最直覺的作法便是這個了，只要用餘數去搜尋是否存在於當前所遍歷的位置，之後剩餘的數，即可找到答案，但這樣子的做法，時間複雜度會近似於 `O(n²)` 了。

* 3.2. 暫存 map 搜尋

每一次遍歷運算的時候，把待確認值放在一個變數 `map`，將待確認值作為 `key`，而 Index 作為 `value` ，每次運算完之後，都將運算結果儲存到這個 `map` 裡面。

當今天運算到 `b` 的時候，由於 `a` 已經被存入到 `map`，`map[target - nums[index]]` 就可以輕易找出答案並回傳了，時間複雜度也是 `O(n)`了。


當然，最後不忘了要回傳的是 `a` 和 `b` 在 `nums` 的索引值。

## 實作

很明顯的，最好的做法是使用 **暫存 map 搜尋**，接下來要做的，便是將上述過程轉化程式碼便是如下：

```javascript
var twoSum = function(nums, target) {
    let map = {};
    for (let i=0;i<nums.length;i++) {
        if(map[target - nums[i]] > -1){
            return [map[target - nums[i]], i];
        }
        map[nums[i]] = i;
    }
};
// 驗算
// console.log(twoSum([2, 7, 11, 15], 9));
```

## 其他

### GitHub 紀錄

此次練習被收錄在我的 GitHub [點我連結](https://github.com/mpp21x/algorithm-exercise/tree/master/1.twoSum)。

### nums 的 length

如果 `nums` 長度很大，可考慮把 `length` 塞給一個變數，避免每次都要運算陣列長度

### 關於 **3.1 遍歷搜尋** 的時間複雜度運算的 `O(n²)` 是怎麼算出來的？

假設 `nums` 長度為 3：用此搜尋在需要花到最多時間的情境下，其步驟為：

* 取出第 1 個數字 ，比較第 2 個數字，比較第 3 個數字： 花了 3 次操作。
* 取出第 2 個數字 ，比較第 3 個數字： 用了 2 次操作
* 總共 5 次操作
```
1:2,3
2:3
```

不難發現出這 5 次操作與參數 n 的關係可以用一個類似等差數列可以求得，用數學符號表示即為：

* `n` 為項數(也就是 `nums` 的長度)
* $a\mathop{}\nolimits_{1}$ 為首項：固定為 1。
* $a\mathop{}\nolimits_{n}$ 為末項：代表 `nums` 長度，如果 `nums = [1,2,3]` 的話，則為 3。
* $S\mathop{}\nolimits_{n}$ 代表總耗費步驟：以 `nums = [1,2,3]` 為例的話，則是 5。

$S\mathop{}\nolimits_{n}=\frac{n \left( a\mathop{}\nolimits_{1}+a\mathop{}\nolimits_{n} \right) }{2} - 1$

不難發現以上公式當中，次方(或平方)最高的便是 $n \left( a\mathop{}\nolimits_{1}+a\mathop{}\nolimits_{n} \right)$ ，這個函式幾乎等同 `O(n²)` 了


### 關於 LaTex：

為了要在 Hexo 用出 LaTex 公式，花了我四個小時多，找了許多篇文章設定...

* [在 Hexo 博客中写出好看的Latex 公式](https://zhuanlan.zhihu.com/p/105986034)
    * 更換渲染引擎即可完成，但這會導致所有的 prismjs 的程式碼渲染失去對應的語言顏色，只好作罷。
    
找到另一個方法，[在此](https://blog.yusanshi.com/2020-01-28-enable-latex-for-hexo/)，只需要安裝以下指令：

```
npm install hexo-renderer-mathjax --save
```      

然後再搭配以下兩篇服用即可：

* [关于 hexo-renderer-kramed 插件导致语法高亮失效的问题](https://github.com/blinkfox/hexo-theme-matery/issues/23)
* [Hexo下mathjax的转义问题](http://shomy.top/2016/10/22/hexo-markdown-mathjax/)

總結就是，需要修改 `nodes_modules/marked/lib/marked.js` 的兩個地方，主要就是把 `\\` 移除即可：

#### escape

```
escape: /^\\([\\`*{}\[\]()# +\-.!_>])/,
```

改成

```	
escape: /^\\([`*{}\[\]()# +\-.!_>])/,
```

#### em

```
em: /^\b_((?:[^_]|__)+?)_\b|^\*((?:\*\*|[\s\S])+?)\*(?!\*)/,
```

改成

```	
em:/^\*((?:\*\*|[\s\S])+?)\*(?!\*)/,
```

## 其他

### GitHub 紀錄

此次練習被收錄在我的 GitHub [點我連結](https://github.com/mpp21x/algorithm-exercise/tree/master/1.twoSum)。
